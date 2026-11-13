<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/Database/Connection.php';
require_once __DIR__ . '/../src/Repositories/Students/StudentRepository.php';
require_once __DIR__ . '/../src/Repositories/Logs/ActivityLogRepository.php';
require_once __DIR__ . '/../src/Services/Students/StudentService.php';
require_once __DIR__ . '/../src/Validators/Students/StudentValidator.php';
require_once __DIR__ . '/../src/Controllers/Students/StudentController.php';
require_once __DIR__ . '/../src/Support/JsonResponse.php';

use App\Database\Connection;
use App\Repositories\Students\StudentRepository;
use App\Repositories\Logs\ActivityLogRepository;
use App\Services\Students\StudentService;
use App\Controllers\Students\StudentController;
use App\Support\JsonResponse;

// CORS
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if ($origin === 'http://localhost:5173') {
    header('Access-Control-Allow-Origin: http://localhost:5173');
    header('Vary: Origin');
    header('Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
}

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// CORS preflight request
if ($method === 'OPTIONS') {
    http_response_code(204);
    exit;
}

try {

    // GET /api/health
    if ($method === 'GET' && $path === '/api/health') {
        JsonResponse::send(200, [
            'success' => true,
            'message' => 'EduCore API is running'
        ]);
    }

    // Match student routes
    $isCollection = $path === '/api/students';

    $isStudent = preg_match(
        '#^/api/students/([1-9][0-9]*)$#',
        $path,
        $matches
    ) === 1;

    if ($isCollection || $isStudent) {

        // One shared PDO connection
        $pdo = Connection::make();

        // Repositories
        $studentRepository = new StudentRepository($pdo);
        $logRepository = new ActivityLogRepository($pdo);

        // Service
        $studentService = new StudentService(
            $pdo,
            $studentRepository,
            $logRepository
        );

        // Controller
        $controller = new StudentController(
            $studentRepository,
            $studentService
        );

        // GET all students
        if ($isCollection && $method === 'GET') {
            $controller->index();
        }

        // POST new student
        if ($isCollection && $method === 'POST') {
            $controller->store();
        }

        if ($isStudent) {
            $id = (int) $matches[1];

            // GET student by ID
            if ($method === 'GET') {
                $controller->show($id);
            }

            // PATCH student
            if ($method === 'PATCH') {
                $controller->update($id);
            }

            // DELETE student
            if ($method === 'DELETE') {
                $controller->destroy($id);
            }
        }

        header('Allow: ' . (
            $isCollection
            ? 'GET, POST'
            : 'GET, PATCH, DELETE'
        ));

        JsonResponse::send(405, [
            'success' => false,
            'message' => 'Method not allowed'
        ]);
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
