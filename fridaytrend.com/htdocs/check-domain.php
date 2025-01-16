<?php
$websiteURL = "https://fridaytrend.com"; // Main website to check
$fallbackURL = "https://coupon.lovestoblog.com"; // Fallback URL

// Initialize cURL
$ch = curl_init($websiteURL);

// Set cURL options
curl_setopt($ch, CURLOPT_NOBODY, true); // Only fetch headers
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Don't output response
curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Timeout in seconds
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // HTTP status code

curl_close($ch);

// Check if the main URL is down
if ($httpCode >= 200 && $httpCode < 300) {
// Website is up
echo "Website is up.";
exit;
} else {
// Website is down; redirect to fallback URL
header("Location: $fallbackURL");
exit;
}


?>