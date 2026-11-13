<?php
declare(strict_types=1);

// ==========================================
// IMPORTS
// ==========================================

// Database
use App\Database\Connection;

// Repositories
use App\Repositories\Students\StudentRepository;
use App\Repositories\Teachers\TeacherRepository;
use App\Repositories\Subjects\SubjectRepository;
use App\Repositories\Logs\ActivityLogRepository;

// Services
use App\Services\Students\StudentService;
use App\Services\Teachers\TeacherService;
use App\Services\Subjects\SubjectService;
// Controllers
use App\Controllers\Students\StudentController;
use App\Controllers\Teachers\TeacherController;
use App\Controllers\Subjects\SubjectController;

// Support
use App\Support\JsonResponse;


// ==========================================
// 1. LOAD RESPONSE HELPER
// ==========================================

require_once __DIR__ . '/../src/Support/JsonResponse.php';
// ==========================================
// 2. CORS CONFIGURATION
// ==========================================

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

header('Vary: Origin');

if ($origin === 'http://localhost:5173') {
    header('Access-Control-Allow-Origin: http://localhost:5173');
    header('Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
}


// ==========================================
// 3. REQUEST INFORMATION
// ==========================================

$method = $_SERVER['REQUEST_METHOD'];

$path = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
) ?: '/';


// Handle CORS preflight
if ($method === 'OPTIONS') {
    http_response_code(204);
    exit;
}


// ==========================================
// 4. MAIN ROUTER
// ==========================================

try {

    // --------------------------------------
    // HEALTH CHECK
    // GET /api/health
    // --------------------------------------

    if ($path === '/api/health') {

        if ($method !== 'GET') {
            header('Allow: GET');

            JsonResponse::send(405, [
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        JsonResponse::send(200, [
            'success' => true,
            'message' => 'EduCore API is running'
        ]);
    }


    // ======================================
    // 5. LOAD APPLICATION FILES
    // ======================================

    // Database
    require_once __DIR__ . '/../src/Database/Connection.php';

    // Repositories
    require_once __DIR__ . '/../src/Repositories/Students/StudentRepository.php';
    require_once __DIR__ . '/../src/Repositories/Teachers/TeacherRepository.php';
    require_once __DIR__ . '/../src/Repositories/Subjects/SubjectRepository.php';
    require_once __DIR__ . '/../src/Repositories/Logs/ActivityLogRepository.php';

    // Services
    require_once __DIR__ . '/../src/Services/Students/StudentService.php';
    require_once __DIR__ . '/../src/Services/Teachers/TeacherService.php';
    require_once __DIR__ . '/../src/Services/Subjects/SubjectService.php';

    // Validators
    require_once __DIR__ . '/../src/Validators/Students/StudentValidator.php';
    require_once __DIR__ . '/../src/Validators/Teachers/TeacherValidator.php';
    require_once __DIR__ . '/../src/Validators/Subjects/SubjectValidator.php';

    // Controllers
    require_once __DIR__ . '/../src/Controllers/Students/StudentController.php';
    require_once __DIR__ . '/../src/Controllers/Teachers/TeacherController.php';
    require_once __DIR__ . '/../src/Controllers/Subjects/SubjectController.php';


    // ======================================
    // 6. SUBJECT ROUTES
    // ======================================

    // GET  /api/subjects
    // POST /api/subjects

    if ($path === '/api/subjects') {

        $allowedMethods = ['GET', 'POST'];

        if (!in_array($method, $allowedMethods, true)) {
            header('Allow: GET, POST');

            JsonResponse::send(405, [
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        // Database connection

        $pdo = Connection::make();

        $subjectRepository = new SubjectRepository($pdo);
        $logRepository = new ActivityLogRepository($pdo);

        $subjectService = new SubjectService(
            $pdo,
            $subjectRepository,
            $logRepository
        );

        $subjectController = new SubjectController(
            $subjectRepository,
            $subjectService
        );

        // GET /api/subjects
        if ($method === 'GET') {
            $subjectController->index();
        }

        // POST /api/subjects
        if ($method === 'POST') {
            $subjectController->store();
        }

    }


    // ======================================
    // 7. STUDENT ROUTES
    // ======================================

    // Collection: /api/students
    $isStudentCollection = $path === '/api/students';

    // Item: /api/students/{id}
    $isStudentItem = preg_match(
        '#^/api/students/([1-9][0-9]*)$#',
        $path,
        $studentMatches
    ) === 1;


    if ($isStudentCollection || $isStudentItem) {

        $allowedMethods = $isStudentCollection
            ? ['GET', 'POST']
            : ['GET', 'PATCH', 'DELETE'];

        if (!in_array($method, $allowedMethods, true)) {

            header(
                'Allow: ' . implode(', ', $allowedMethods)
            );

            JsonResponse::send(405, [
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        // Validate student ID
        if ($isStudentItem) {

            $id = filter_var(
                $studentMatches[1],
                FILTER_VALIDATE_INT,
                ['options' => ['min_range' => 1]]
            );

            if ($id === false) {
                JsonResponse::send(400, [
                    'success' => false,
                    'message' => 'Invalid student ID'
                ]);
            }
        }

        // Shared connection
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
        $studentController = new StudentController(
            $studentRepository,
            $studentService
        );


        // GET /api/students
        if ($isStudentCollection && $method === 'GET') {
            $studentController->index();
        }

        // POST /api/students
        if ($isStudentCollection && $method === 'POST') {
            $studentController->store();
        }

        if ($isStudentItem) {

            // GET /api/students/{id}
            if ($method === 'GET') {
                $studentController->show($id);
            }

            // PATCH /api/students/{id}
            if ($method === 'PATCH') {
                $studentController->update($id);
            }

            // DELETE /api/students/{id}
            if ($method === 'DELETE') {
                $studentController->destroy($id);
            }
        }
    }


    // ======================================
    // 8. TEACHER ROUTES
    // ======================================

    // Collection: /api/teachers
    $isTeacherCollection = $path === '/api/teachers';

    // Item: /api/teachers/{id}
    $isTeacherItem = preg_match(
        '#^/api/teachers/([1-9][0-9]*)$#',
        $path,
        $teacherMatches
    ) === 1;


    if ($isTeacherCollection || $isTeacherItem) {

        $allowedMethods = $isTeacherCollection
            ? ['GET', 'POST']
            : ['GET', 'PATCH', 'DELETE'];

        if (!in_array($method, $allowedMethods, true)) {

            header(
                'Allow: ' . implode(', ', $allowedMethods)
            );

            JsonResponse::send(405, [
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }

        // Validate teacher ID
        if ($isTeacherItem) {

            $id = filter_var(
                $teacherMatches[1],
                FILTER_VALIDATE_INT,
                ['options' => ['min_range' => 1]]
            );

            if ($id === false) {
                JsonResponse::send(400, [
                    'success' => false,
                    'message' => 'Invalid teacher ID'
                ]);
            }
        }

        // Shared connection
        $pdo = Connection::make();

        // Repositories
        $teacherRepository = new TeacherRepository($pdo);
        $logRepository = new ActivityLogRepository($pdo);

        // Service
        $teacherService = new TeacherService(
            $pdo,
            $teacherRepository,
            $logRepository
        );

        // Controller
        $teacherController = new TeacherController(
            $teacherRepository,
            $teacherService
        );


        // GET /api/teachers
        if ($isTeacherCollection && $method === 'GET') {
            $teacherController->index();
        }

        // POST /api/teachers
        if ($isTeacherCollection && $method === 'POST') {
            $teacherController->store();
        }

        if ($isTeacherItem) {

            // GET /api/teachers/{id}
            if ($method === 'GET') {
                $teacherController->show($id);
            }

            // PATCH /api/teachers/{id}
            if ($method === 'PATCH') {
                $teacherController->update($id);
            }

            // DELETE /api/teachers/{id}
            if ($method === 'DELETE') {
                $teacherController->destroy($id);
            }
        }
    }


    // ======================================
    // 9. ROUTE NOT FOUND
    // ======================================

    JsonResponse::send(404, [
        'success' => false,
        'message' => 'Route not found'
    ]);


} catch (Throwable $e) {

    // Log detailed error on server
    error_log((string) $e);

    // Send safe error response
    JsonResponse::send(500, [
        'success' => false,
        'message' => 'Internal server error'
    ]);
}
