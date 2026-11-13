<?php

require_once __DIR__ . '/../src/Database/Connection.php';
require_once __DIR__ . '/../src/Repositories/Students/StudentRepository.php';
require_once __DIR__ . '/../src/Support/JsonResponse.php';
require_once __DIR__ . '/../src/Controllers/Students/StudentController.php';

use App\Database\Connection;
use App\Repositories\Students\StudentRepository;
use App\Controllers\Students\StudentController;
use App\Support\JsonResponse;

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if ($origin === "http://localhost:5173") {
    header('Access-Control-Allow-Origin: http://localhost:5173');
    header('Vary: Origin');
    header('Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
}


$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($method === 'OPTIONS') {
    http_response_code(204);
    exit;
}

try {
    if ($method === 'GET' && $path === '/api/health') {
        JsonResponse::send(200, [
            'success' => true,
            'message' => 'EduCore API is running'
        ]);
    }

    if ($method === 'GET' && $path === '/api/students') {
        $pdo = Connection::make();

        $repository = new StudentRepository($pdo);
        $controller = new StudentController($repository);

        $controller->index();
    }

    JsonResponse::send(404, [
        'success' => false,
        'message' => 'Route not found'
    ]);

} catch (Throwable $e) {
    error_log((string) $e);

    JsonResponse::send(500, [
        'success' => false,
        'message' => 'Internal server error'
    ]);
}