<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AffiliTracker - Affiliate Link Tester</title>
    <link rel="stylesheet" href="layoutstyle.css">
</head>

<body>

    <!-- HEADER -->
    <header class="header">
        <div class="header-inner">
            <div>
                <div class="logo">Zet<span>ta</span>Test</div>
                <span class="logo-small">by Your Brand</span>
            </div>
        </div>
    </header>

    <!-- MAIN -->
    <main class="container">
        <!-- TABS -->
        <div class="tabs">
            <div class="tab active" id="trackingTab" onclick="switchTab('tracking')">
                Tracking Link
            </div>
        </div>

        <!-- TESTER -->
        <div class="tester-box">
            <input type="url" name="mainInput" id="trackingUrl" class="url-input"
                placeholder="Enter your affiliate/tracking link" autocomplete="off" required>
            <div class="form-row">
                <select id="device" name="device" class="select" required>
                    <option value="">Select Device</option>
                    <option value="android">Android</option>
                    <option value="iphone">iPhone</option>
                    <option value="ipad">iPad</option>
                    <option value="desktop">Desktop</option>
                    <option value="windowsPhone">Windows Phone</option>
                    <option value="blackberry">Blackberry</option>
                    <option value="mac">Mac</option>
                    <option value="linux">Linux</option>
                </select>

                <select id="country" name="country" class="select" required>
                    <option value="">Select Country</option>
                    <option value="us">United States</option>
                    <option value="in">India</option>
                    <option value="uk">United Kingdom</option>
                    <option value="ca">Canada</option>
                    <option value="au">Australia</option>
                    <option value="de">Germany</option>
                </select>
                <button type="button" class="submit-btn" id="submitBtn" onclick="runTest()">
                    SUBMIT
                </button>
            </div>

            <!-- ADVANCED -->
            <div class="advanced">
                <button type="button" class="advanced-button" onclick="toggleAdvanced()">Advanced</button>
                <div class="advanced-content" id="advancedContent">
                    <div class="advanced-grid">
                        <div>
                            <label class="advanced-label">Device type</label>
                            <select id="advancedDev" name="advancedDevice" class="select">
                                <optgroup label="Android">
                                    <option value="android">15 (Latest)</option>
                                    <option value="android14">14</option>
                                    <option value="android13">13</option>
                                    <option value="android12">12</option>
                                    <option value="android11">11</option>
                                    <option value="android10">10</option>
                                    <option value="android9">9 Pie</option>
                                    <option value="android8">8 Oreo</option>
                                    <option value="android7">7.1.1 Nougat</option>
                                    <option value="android6">6.0 Marshmallow</option>
                                    <option value="android5">5.0 Lollipop</option>
                                    <option value="android44">4.4 KitKat</option>
                                    <option value="android4">4.1 Jellybean</option>
                                </optgroup>
                                <optgroup label="iPhone">
                                    <option value="iphone">iOS 18 (Latest)</option>
                                    <option value="iphone17">iOS 17</option>
                                    <option value="iphone16">iOS 16</option>
                                    <option value="iphone15">iOS 15</option>
                                    <option value="iphone14">iOS 14</option>
                                    <option value="iphone13">iOS 13</option>
                                    <option value="iphone124">iOS 12.4</option>
                                    <option value="iphone12">iOS 12</option>
                                    <option value="iphone11">iOS 11</option>
                                    <option value="iphone10">iOS 10.1</option>
                                    <option value="iphone9">iOS 9.0</option>
                                    <option value="iphone8">iOS 8.0</option>
                                    <option value="iphone7">iOS 7.0</option>
                                </optgroup>
                            </select>
                        </div>
                        <div>
                            <label class="advanced-label">City Targeting</label>
                            <select id="advancedCity" name="city" class="select">
                                <option value="">City Targeting - Select a country</option>
                            </select>
                        </div>
                        <div>
                            <label class="advanced-label">Connection</label>
                            <select id="advancedNetType" name="netType" class="select">
                                <option value="wifi">Wifi Connection</option>
                                <option value="3g">3G/4G Connection</option>
                            </select>
                        </div>
                        <div>
                            <label class="advanced-label">Select Carrier</label>
                            <select id="advancedASN" name="asn" class="select" disabled>
                                <option value="">Select Carrier (3G Connection)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RESULT TYPE -->
            <div class="result-options">
                Show result as:
                <label>
                    <input type="radio" name="resultType" value="redirect" checked>
                    Redirections
                </label>
            </div>
        </div>

        <!-- LOADING -->
        <div class="loading" id="loading">
            <div class="spinner"></div>
            Checking redirect chain...
        </div>
        <div class="error-box" id="errorBox"></div>
        <section id="result">
            <div class="result-title">Redirect Results</div>
            <div class="redirect-list" id="redirectList"></div>
        </section>
    </main>

    <script>
        let currentTab = "tracking";
        function switchTab(tab) {
            currentTab = tab;
            const trackingTab = document.getElementById("trackingTab");
            const input = document.getElementById("trackingUrl");

            if (tab === "tracking") {
                trackingTab.classList.add("active");
                input.type = "url";
                input.placeholder = "Enter your affiliate/tracking link";
            } else {
                trackingTab.classList.remove("active");
                input.type = "text";
                input.placeholder = "Paste your HTML tracking tag";
            }
        }

        // ADVANCED
        function toggleAdvanced() {
            document.getElementById("advancedContent").classList.toggle("show");
        }

        //  CITY DATA
        const cityData = {
            us: ["New York", "Los Angeles", "Chicago", "Houston", "Miami", "San Francisco", "Dallas"],
            in: ["Delhi", "Mumbai", "Bangalore", "Hyderabad", "Chennai", "Kolkata", "Pune", "Ahmedabad"],
            uk: ["London", "Manchester", "Birmingham", "Liverpool", "Leeds", "Bristol"],
            ca: ["Toronto", "Vancouver", "Montreal", "Calgary", "Ottawa", "Edmonton"],
            au: ["Sydney", "Melbourne", "Brisbane", "Perth", "Adelaide", "Canberra"],
            de: ["Berlin", "Munich", "Hamburg", "Frankfurt", "Cologne", "Stuttgart"]
        };

        // CARRIER DATA
        const carrierData = {
            us: ["AT&T", "Verizon", "T-Mobile"],
            in: ["Jio", "Airtel", "Vi", "BSNL"],
            uk: ["EE", "O2", "Vodafone", "Three"],
            ca: ["Rogers", "Bell", "Telus"],
            au: ["Telstra", "Optus", "Vodafone"],
            de: ["Deutsche Telekom", "Vodafone", "O2"]
        };

        // COUNTRY CHANGE
        document.getElementById("country").addEventListener("change", function () {
            updateCities(this.value);
            updateCarriers(this.value);
        }
        );

        // UPDATE CITY
        function updateCities(country) {
            const citySelect = document.getElementById("advancedCity");
            citySelect.innerHTML = "";
            const defaultOption = document.createElement("option");
            defaultOption.value = "";
            defaultOption.textContent = "City Targeting - Select city";
            citySelect.appendChild(defaultOption);
            if (!country || !cityData[country]) {
                return;
            }

            cityData[country].forEach(city => {
                const option = document.createElement("option");
                option.value = city.toLowerCase().replace(/\s+/g, "_");
                option.textContent = city;
                citySelect.appendChild(option);
            });
        }

        //  UPDATE CARRIER
        function updateCarriers(country) {
            const carrierSelect = document.getElementById("advancedASN");
            carrierSelect.innerHTML = "";
            const defaultOption = document.createElement("option");
            defaultOption.value = "";
            defaultOption.textContent = "Select Carrier";
            carrierSelect.appendChild(defaultOption);
            if (!country || !carrierData[country]) {
                carrierSelect.disabled = true;
                return;
            }

            carrierData[country].forEach(carrier => {
                const option = document.createElement("option");
                option.value = carrier.toLowerCase().replace(/\s+/g, "_");
                option.textContent = carrier;
                carrierSelect.appendChild(option);
            });

            carrierSelect.disabled = document.getElementById("advancedNetType").value !== "3g";
        }

        // CONNECTION
        document.getElementById("advancedNetType").addEventListener("change", function () {
            const carrier = document.getElementById("advancedASN");
            if (this.value === "3g") {
                carrier.disabled = false;
                updateCarriers(document.getElementById("country").value);
            } else {
                carrier.disabled = true;
                carrier.value = "";
            }
        }
        );

        // URL VALIDATION
        function isValidUrl(value) {
            try {
                const url = new URL(value);
                return (url.protocol === "http:" || url.protocol === "https:");
            } catch (error) {
                return false;
            }
        }

        //  ESCAPE HTML
        function escapeHtml(value) {
            if (value === null || value === undefined) {
                return "";
            }

            return String(value)
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        //  SAFE URL LINK
        function makeUrlLink(url) {
            if (!url || url === "-") {
                return "-";
            }

            return `
                <a href="${escapeHtml(url)}" target="_blank" rel="noopener noreferrer"class="url-link">
                    ${escapeHtml(url)}
                </a>
            `;
        }

        // STATUS CLASS
        function statusClass(status) {
            status = Number(status);
            if (status >= 200 && status < 300) {
                return "status-2xx";
            }

            if (status >= 300 && status < 400) {
                return "status-3xx";
            }

            if (status >= 400 && status < 500) {
                return "status-4xx";
            }

            if (status >= 500 && status < 600) {
                return "status-5xx";
            }
            return "";
        }


        // STATUS TEXT
        function getStatusText(status) {
            status = Number(status);
            if (status >= 200 && status < 300) {
                return "Success";
            }

            if (status >= 300 && status < 400) {
                return "Redirect";
            }

            if (status >= 400 && status < 500) {
                return "Client Error";
            }

            if (status >= 500 && status < 600) {
                return "Server Error";
            }
            return "Unknown";
        }


        // GET FORM DATA
        function getFormData() {
            return {
                mainInput: document.getElementById("trackingUrl").value.trim(),
                device: document.getElementById("device").value,
                country: document.getElementById("country").value,
                advancedDevice: document.getElementById("advancedDev").value,
                city: document.getElementById("advancedCity").value,
                connection: document.getElementById("advancedNetType").value,
                carrier: document.getElementById("advancedASN").value,
                resultType: document.querySelector('input[name="resultType"]:checked')?.value || "redirect"
            };
        }

        // VALIDATE
        function validateForm(formData) {
            if (!formData.mainInput) {
                showError("Tracking URL is required.");
                document.getElementById("trackingUrl").focus();
                return false;
            }

            if (currentTab === "tracking" && !isValidUrl(formData.mainInput)) {
                showError("Please enter a valid HTTP or HTTPS URL.");
                return false;
            }

            if (!formData.device) {
                showError("Please select a device.");
                return false;
            }

            if (!formData.country) {
                showError("Please select a country.");
                return false;
            }

            if (formData.connection === "3g" && !formData.carrier) {
                showError("Please select a carrier for the 3G/4G connection.");
                return false;
            }
            return true;
        }

        // ERROR
        function showError(message) {
            const errorBox = document.getElementById("errorBox");
            errorBox.textContent = message;
            errorBox.style.display = "block";
            errorBox.scrollIntoView({ behavior: "smooth", block: "center" });
        }

        function clearError() {
            const errorBox = document.getElementById("errorBox");
            errorBox.style.display = "none";
            errorBox.textContent = "";
        }

        //  DISPLAY REDIRECT LOOP
        function displayRedirectLoop(data, requestData) {
            const result = document.getElementById("result");
            const list = document.getElementById("redirectList");
            const redirects = Array.isArray(data.redirects)
                ? data.redirects
                : [];

            list.innerHTML = "";

            let html = `<div class="loop-chain">`;
            const startUrl = data.url || requestData.mainInput || "";
            if (startUrl) {
                html += `<div class="loop-url">${makeUrlLink(startUrl)}</div>`;
            }

            // REDIRECTS
            if (redirects.length > 0) {
                redirects.forEach((redirect, index) => {
                    const to = redirect.to || redirect.location || "-";
                    html += `<div class="loop-url">${makeUrlLink(to)}</div>`;
                }
                );

            } else {

                html += `
                    <div class="loop-item">
                        <div class="loop-item-top">
                            <span class="loop-number">1</span>
                            <strong>No redirect records returned</strong>
                        </div>
                    </div>
                `;
            }

            list.innerHTML = html;
            result.style.display = "block";
            result.scrollIntoView({ behavior: "smooth", block: "start" });
        }

        //  RUN TEST
        async function runTest() {
            const loading = document.getElementById("loading");
            const result = document.getElementById("result");
            const submitBtn = document.getElementById("submitBtn");
            const formData = getFormData();

            // VALIDATE
            if (!validateForm(formData)) {
                return;
            }

            clearError();
            result.style.display = "none";
            loading.style.display = "block";
            submitBtn.disabled = true;

            try {
                const requestData = {
                    url: formData.mainInput,
                    device: formData.device,
                    country: formData.country,
                    advanced_device: formData.advancedDevice,
                    city: formData.city,
                    connection: formData.connection,
                    carrier: formData.carrier,
                    result_type: formData.resultType,
                    max_hops: 1000
                };

                const response = await fetch("redirect-check.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify(requestData)
                }
                );

                const data = await response.json().catch(() => null);

                //  REDIRECT LOOP
                if (data && data.success === false && String(data.message || "")
                    .toLowerCase()
                    .includes("redirect loop detected")
                ) {
                    displayRedirectLoop(data, requestData);
                    return;
                }

                // MAX HOPS
                if (data && data.success === false && String(data.message || ""
                )
                    .toLowerCase()
                    .includes("maximum redirect hops exceeded")
                ) {
                    displayRedirectLoop(data, requestData);
                    return;
                }

                // OTHER HTTP ERROR
                if (!response.ok || !data) {
                    throw new Error(
                        data?.message ||
                        "Internal redirect checker returned HTTP " +
                        response.status
                    );
                }

                // OTHER API ERROR
                if (data.success !== true) {
                    throw new Error(
                        data.message ||
                        "Unable to check this URL."
                    );
                }

                displayResults(data, requestData);
            } catch (error) {
                console.error("Redirect Test Error:", error);
                showError(error.message || "Unable to check this URL. Please try again.");
            } finally {
                loading.style.display = "none";
                submitBtn.disabled = false;
            }
        }


        // NORMAL REDIRECT RESULTS
        function displayResults(data, requestData) {
            const result = document.getElementById("result");
            const list = document.getElementById("redirectList");
            const redirects = Array.isArray(data.redirects)
                ? data.redirects
                : [];
            const finalResult = data.final_result || {};
            const MAX_REDIRECTS = 10;
            const visibleRedirects = redirects.slice(0, MAX_REDIRECTS);

            list.innerHTML = "";

            //    DIRECT RESPONSE
            if (visibleRedirects.length === 0) {
                const status = Number(finalResult.status_code || 0);
                const directUrl = data.url || requestData.mainInput || "-";

                list.innerHTML = `
                    <div class="redirect-card">
                        <div class="redirect-header">
                            <span class="redirect-number">1.</span>
                            <span class="status ${statusClass(status)}">${status || "-"}</span>
                        </div>
                        <div class="url-line">
                            <strong>URL:</strong><br>${makeUrlLink(directUrl)}
                        </div>
                        <div class="duration">${escapeHtml(getStatusText(status))}</div>
                    </div>
                `;

            } else {

                // REDIRECT LIST
                visibleRedirects.forEach((redirect, index) => {
                    const to = redirect.to || redirect.location || "-";
                    list.innerHTML += `<div class="url-line">${makeUrlLink(to)}</div>`;
                }
                );
            }

            // FINAL DESTINATION
            const finalUrl = finalResult.final_url || data.final_url ||
                data.url || requestData.mainInput || "-";
            const finalStatus = Number(finalResult.status_code || 0);
            const lastRedirect = visibleRedirects[visibleRedirects.length - 1];
            const lastTo = lastRedirect
                ? (lastRedirect.to || lastRedirect.location || lastRedirect.url || "")
                : "";

            if (finalUrl && finalUrl !== lastTo) {
                list.innerHTML += `
                    <div class="final-card">
                        <div class="final-title">Final Destination</div>
                        <div class="url-line">${makeUrlLink(finalUrl)}</div>
                        <div class="endpoint-status">
                            <span class="status ${statusClass(finalStatus)}">${finalStatus || "-"}</span>
                            &nbsp;
                            ${escapeHtml(getStatusText(finalStatus))}
                        </div>
                    </div>
                `;
            }

            // SHOW
            result.style.display = "block";
            result.scrollIntoView({ behavior: "smooth", block: "start" });
        }

        // INITIAL COUNTRY
        document.addEventListener("DOMContentLoaded", function () {
            const country = document.getElementById("country").value;
            if (country) {
                updateCities(country);
                updateCarriers(country);
            }
        }
        );
    </script>
</body>

</html>