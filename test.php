<?php
// Allow requests from any origin (You can restrict this to specific domains)
header("Access-Control-Allow-Origin: *");

// Specify allowed request methods
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// Specify allowed headers
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight request (OPTIONS method)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Sample JSON response
$response = [
    "status"    => "success",
    "message"   => "CORS-enabled PHP API is working!",
    "timestamp" => date("Y-m-d H:i:s"),
];

// Send JSON response
header("Content-Type: application/json");
echo json_encode($response);