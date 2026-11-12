
<?php
declare(strict_types=1);

header("Content-Type: application/json; charset=utf-8");

// Allow our React frontend during local development
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$method = $_SERVER["REQUEST_METHOD"];
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

// Handle browser preflight requests
if ($method === "OPTIONS") {
    http_response_code(204);
    exit;
}

// Health endpoint
if ($method === "GET" && $path === "/api/health") {
    http_response_code(200);

    echo json_encode([
        "success" => true,
        "message" => "Takamol API is running"
    ]);

    exit;
}

// Not found
http_response_code(404);

echo json_encode([
    "success" => false,
    "message" => "Route not found"
]);
