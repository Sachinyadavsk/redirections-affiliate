<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['success' => false,' message' => 'Only POST requests are allowed.'], 405);
}

if (!function_exists('curl_init')) {
    json_response(['success' => false,' message' => 'PHP cURL extension is not enabled on the server.'], 500);
}

$raw = file_get_contents('php://input');
$input = json_decode($raw ?: '', true);

if (!is_array($input)) {
    json_response(['success' => false,' message' => 'Invalid JSON request.'], 400);
}

$url = trim((string)($input['url'] ?? ''));
$maxHops = (int)($input['max_hops'] ?? 1000);
$maxHops = max(1, min($maxHops, 1000));

if ($url === '') {
    json_response(['success' => false,' message' => 'URL is required.'], 400);
}

try {
    $url = normalize_url($url);
    validate_public_url($url);
    
    $startedAt = microtime(true);
    $redirects = [];
    $visited = [];
    $currentUrl = $url;
    $finalResult = null;

    for ($hop = 0; $hop <= $maxHops; $hop++) {
        $normalizedForLoop = strtolower(remove_fragment($currentUrl));

        if (isset($visited[$normalizedForLoop])) {
            json_response([
                'success' => false,
                'message' => 'Redirect loop detected.',
                'url' => $url,
                'redirects' => $redirects,
                'final_url' => $currentUrl,
                'total_duration' => elapsed_ms($startedAt),
                'hops' => count($redirects)
            ], 508);
        }

        $visited[$normalizedForLoop] = true;
        validate_public_url($currentUrl);

        $response = fetch_get_response($currentUrl);
        $status = $response['status'];
        $location = $response['location'];
        $duration = $response['duration'];

        if (is_redirect_status($status) && $location !== null && $location !== '') {
            if (count($redirects) >= $maxHops) {
                json_response([
                    'success' => false,
                    'message' => 'Maximum redirect hops exceeded.',
                    'url' => $url,
                    'redirects' => $redirects,
                    'final_url' => $currentUrl,
                    'total_duration' => elapsed_ms($startedAt),
                    'hops' => count($redirects)
                ], 508);
            }

            $nextUrl = resolve_url($currentUrl, $location);
            validate_public_url($nextUrl);

            $redirects[] = [
                'to' => $nextUrl,
                'status_code' => $status,
                'duration' => $duration,
                'location' => $location
            ];

            $currentUrl = $nextUrl;
            continue;
        }

        $finalResult = [
            'final_url' => $currentUrl,
            'status_code' => $status,
            'duration' => $duration,
            'content_type' => $response['content_type'],
            'headers' => $response['headers']
        ];
        break;
    }

    if ($finalResult === null) {
        json_response([
            'success' => false,
            'message' => 'Maximum redirect hops exceeded.',
            'url' => $url,
            'redirects' => $redirects,
            'final_url' => $currentUrl,
            'total_duration' => elapsed_ms($startedAt),
            'hops' => count($redirects)
        ], 508);
    }

    json_response([
        'success' => true,
        'url' => $url,
        'redirects' => $redirects,
        'final_result' => $finalResult,
        'final_url' => $finalResult['final_url'],
        'total_duration' => elapsed_ms($startedAt),
        'hops' => count($redirects)
    ]);

} catch (Throwable $e) {
    json_response([
        'success' => false,
        'message' => $e->getMessage() ?: 'Unable to check this URL.'
    ], 400);
}

/**
 * Send JSON and stop execution.
 */
function json_response(array $data, int $status = 200): never
{
    http_response_code($status);
    echo json_encode(
        $data,
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    );
    exit;
}

/**
 * Validate and normalize an HTTP/HTTPS URL.
 */
function normalize_url(string $url): string
{
    $url = trim($url);
    if (!preg_match('~^https?://~i', $url)) {
        $url = 'https://' . $url;
    }

    $parts = parse_url($url);
    if ($parts === false || empty($parts['scheme']) || empty($parts['host'])) {
        throw new RuntimeException('Please enter a valid HTTP/HTTPS URL.');
    }

    $scheme = strtolower($parts['scheme']);
    if (!in_array($scheme, ['http', 'https'], true)) {
        throw new RuntimeException('Only HTTP and HTTPS URLs are allowed.');
    }

    $host = $parts['host'];
    if ($host === '' || strlen($host) > 253) {
        throw new RuntimeException('Invalid URL host.');
    }

    if (isset($parts['user']) || isset($parts['pass'])) {
        throw new RuntimeException('URLs containing username/password are not allowed.');
    }

    return $url;
}

/**
 * SSRF protection: reject localhost/private/reserved destinations.
 * The hostname is resolved and a public IP is pinned for the cURL request.
 */
function validate_public_url(string $url): void
{
    $parts = parse_url($url);
    if ($parts === false || empty($parts['host'])) {
        throw new RuntimeException('Invalid redirect URL.');
    }

    $host = strtolower(rtrim($parts['host'], '.'));
    if ($host === 'localhost' || str_ends_with($host, '.localhost')) {
        throw new RuntimeException('Localhost URLs are not allowed.');
    }

    if (filter_var($host, FILTER_VALIDATE_IP)) {
        if (is_private_or_reserved_ip($host)) {
            throw new RuntimeException('Private or reserved IP addresses are not allowed.');
        }
        return;
    }

    if (!preg_match('/^[a-z0-9.-]+$/i', $host)) {
        throw new RuntimeException('Invalid hostname.');
    }

    $records = @dns_get_record($host, DNS_A | DNS_AAAA);
    if (!is_array($records) || count($records) === 0) {
        throw new RuntimeException('Unable to resolve hostname: ' . $host);
    }

    $hasPublicIp = false;
    foreach ($records as $record) {
        $ip = $record['ip'] ?? ($record['ipv6'] ?? null);
        if ($ip && !is_private_or_reserved_ip($ip)) {
            $hasPublicIp = true;
            break;
        }
    }

    if (!$hasPublicIp) {
        throw new RuntimeException('The URL resolves only to a private or reserved IP address.');
    }
}

/**
 * Return a public IP from DNS records. Used to pin cURL to the validated IP.
 */
function get_public_ip(string $host): ?string
{
    if (filter_var($host, FILTER_VALIDATE_IP)) {
        return is_private_or_reserved_ip($host) ? null : $host;
    }

    $records = @dns_get_record($host, DNS_A | DNS_AAAA);
    if (!is_array($records)) {
        return null;
    }

    foreach ($records as $record) {
        $ip = $record['ip'] ?? ($record['ipv6'] ?? null);
        if ($ip && !is_private_or_reserved_ip($ip)) {
            return $ip;
        }
    }

    return null;
}

/**
 * Detect private, loopback, link-local, multicast and reserved IP ranges.
 */
function is_private_or_reserved_ip(string $ip): bool
{
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        return filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) === false;
    }

    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
        $packed = @inet_pton($ip);
        if ($packed === false) {
            return true;
        }

        // ::1 loopback
        if ($ip === '::1') {
            return true;
        }

        // fc00::/7 unique-local, fe80::/10 link-local, ff00::/8 multicast.
        $first = ord($packed[0]);
        if (($first & 0xfe) === 0xfc) {
            return true;
        }
        if (($first === 0xfe) && ((ord($packed[1]) & 0xc0) === 0x80)) {
            return true;
        }
        if ($first === 0xff) {
            return true;
        }

        return false;
    }

    return true;
}

/**
 * Perform exactly one GET request. Redirect following is disabled.
 */
function fetch_get_response(string $url): array
{
    $parts = parse_url($url);
    if ($parts === false || empty($parts['host'])) {
        throw new RuntimeException('Invalid URL.');
    }

    $host = $parts['host'];
    $scheme = strtolower($parts['scheme'] ?? 'https');
    $port = isset($parts['port'])
        ? (int)$parts['port']
        : ($scheme === 'https' ? 443 : 80);

    $publicIp = get_public_ip($host);
    if ($publicIp === null) {
        throw new RuntimeException('Unable to resolve a public IP for: ' . $host);
    }

    $headers = [];
    $status = 0;
    $headerBlock = '';
    $bodyBytes = 0;
    $maxBodyBytes = 1024 * 1024;
    $start = microtime(true);

    $ch = curl_init($url);
    if ($ch === false) {
        throw new RuntimeException('Unable to initialize cURL.');
    }

    $resolveAddress = $host . ':' . $port . ':' . $publicIp;

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => false,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HEADER => false,
        CURLOPT_NOBODY => false,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPGET => true,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_MAXREDIRS => 0,
        CURLOPT_ENCODING => '',
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_USERAGENT => 'AffiliTracker-Internal-Redirect-Checker/1.0',
        CURLOPT_HTTPHEADER => [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Cache-Control: no-cache'
        ],
        CURLOPT_RESOLVE => [$resolveAddress],
        CURLOPT_PROXY => '',
        CURLOPT_HEADERFUNCTION => function ($curl, string $headerLine) use (&$headers, &$status, &$headerBlock): int {
            $trimmed = trim($headerLine);

            if (preg_match('~^HTTP/\S+\s+(\d{3})~i', $trimmed, $m)) {
                $status = (int)$m[1];
                $headerBlock = '';
                $headers = [];
                return strlen($headerLine);
            }

            $headerBlock .= $headerLine;

            if ($trimmed === '') {
                return strlen($headerLine);
            }

            $pos = strpos($headerLine, ':');
            if ($pos !== false) {
                $name = strtolower(trim(substr($headerLine, 0, $pos)));
                $value = trim(substr($headerLine, $pos + 1));
                $headers[$name] = $value;
            }

            return strlen($headerLine);
        },
        CURLOPT_WRITEFUNCTION => function ($curl, string $chunk) use (&$bodyBytes, $maxBodyBytes): int {
            $bodyBytes += strlen($chunk);
            if ($bodyBytes > $maxBodyBytes) {
                return 0;
            }
            return strlen($chunk);
        }
    ]);

    $ok = curl_exec($ch);
    $curlError = curl_error($ch);
    $curlErrno = curl_errno($ch);
    $contentType = (string)(curl_getinfo($ch, CURLINFO_CONTENT_TYPE) ?: '');
    $httpCode = (int)(curl_getinfo($ch, CURLINFO_HTTP_CODE) ?: $status);
    $effectiveUrl = (string)(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL) ?: $url);
    curl_close($ch);

    $duration = (int)round((microtime(true) - $start) * 1000);

    if ($ok === false || $curlErrno !== 0) {
        if ($bodyBytes > $maxBodyBytes) {
            throw new RuntimeException('Response body exceeded the 1 MB safety limit.');
        }
        throw new RuntimeException(
            'GET request failed: ' . ($curlError ?: 'cURL error ' . $curlErrno)
        );
    }

    if ($httpCode <= 0) {
        throw new RuntimeException('The server did not return a valid HTTP status.');
    }

    return [
        'status' => $httpCode,
        'location' => $headers['location'] ?? null,
        'duration' => $duration,
        'content_type' => $contentType,
        'effective_url' => $effectiveUrl,
        'headers' => $headers
    ];
}

function is_redirect_status(int $status): bool
{
    return in_array($status, [301, 302, 303, 307, 308], true);
}

/**
 * Resolve an absolute, root-relative, path-relative or protocol-relative Location.
 */
function resolve_url(string $base, string $location): string
{
    $location = trim($location);

    if ($location === '') {
        throw new RuntimeException('Redirect Location header is empty.');
    }

    if (preg_match('~^https?://~i', $location)) {
        return normalize_url($location);
    }

    $baseParts = parse_url($base);
    if ($baseParts === false || empty($baseParts['scheme']) || empty($baseParts['host'])) {
        throw new RuntimeException('Unable to resolve redirect target.');
    }

    if (str_starts_with($location, '//')) {
        return normalize_url($baseParts['scheme'] . ':' . $location);
    }

    $scheme = $baseParts['scheme'];
    $host = $baseParts['host'];
    $port = isset($baseParts['port']) ? ':' . $baseParts['port'] : '';

    if (str_starts_with($location, '?')) {
        $basePath = $baseParts['path'] ?? '/';
        return normalize_url($scheme . '://' . $host . $port . $basePath . $location);
    }

    if (str_starts_with($location, '#')) {
        $baseWithoutFragment = remove_fragment($base);
        return normalize_url($baseWithoutFragment . $location);
    }

    $locationParts = parse_url($location);
    if ($locationParts !== false && isset($locationParts['scheme'])) {
        return normalize_url($location);
    }

    $path = $locationParts['path'] ?? $location;
    $query = isset($locationParts['query']) ? '?' . $locationParts['query'] : '';
    $fragment = isset($locationParts['fragment']) ? '#' . $locationParts['fragment'] : '';

    if ($path === '') {
        $path = $baseParts['path'] ?? '/';
    }

    if ($path[0] !== '/') {
        $basePath = $baseParts['path'] ?? '/';
        $directory = preg_replace('~/[^/]*$~', '/', $basePath) ?: '/';
        $path = $directory . $path;
    }

    $path = normalize_path($path);
    return normalize_url(
        $scheme . '://' . $host . $port . $path . $query . $fragment
    );
}

function normalize_path(string $path): string
{
    $segments = explode('/', $path);
    $result = [];

    foreach ($segments as $segment) {
        if ($segment === '' || $segment === '.') {
            continue;
        }
        if ($segment === '..') {
            array_pop($result);
            continue;
        }
        $result[] = $segment;
    }

    return '/' . implode('/', $result);
}

function remove_fragment(string $url): string
{
    $pos = strpos($url, '#');
    return $pos === false ? $url : substr($url, 0, $pos);
}

function elapsed_ms(float $start): int
{
    return (int)round((microtime(true) - $start) * 1000);
}