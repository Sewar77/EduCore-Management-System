<?php
declare(strict_types=1);

namespace App\Controllers\Students;
use App\Services\Students\StudentService;
use App\Repositories\Students\StudentRepository;
use App\Validators\Students\StudentValidator;
use App\Support\JsonResponse;
use JsonException;
use PDOException;

final class StudentController
{
    public function __construct(
        private StudentRepository $students,
        private StudentService $studentService

    ) {
    }

    // GET /api/students
    public function index(): void
    {
        $students = $this->students->all();
        JsonResponse::send(200, [
            'success' => true,
            'data' => $students,
            'message' => empty($students)
                ? 'No students yet'
                : 'Students retrieved successfully'

        ]);
    }

    // GET /api/students/{id}
    public function show(int $id): void
    {
        $student = $this->students->byStudentId($id);

        if ($student === null) {
            JsonResponse::send(404, [
                'success' => false,
                'message' => 'Student not found'
            ]);
        }
        JsonResponse::send(200, [
            'success' => true,
            'data' => $student
        ]);
    }

    // POST /api/students
    public function store(): void
    {
        $data = $this->readJsonBody();

        $errors = StudentValidator::validate($data);

        if (!empty($errors)) {
            JsonResponse::send(422, [
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors
            ]);
        }

        try {
            $student = $this->studentService->createStudent($data);
        } catch (PDOException $e) {
            if ((string) $e->getCode() === '23505') {
                JsonResponse::send(409, [
                    'success' => false,
                    'message' => 'Student email already exists'
                ]);
            }

            throw $e;
        }

        JsonResponse::send(201, [
            'success' => true,
            'message' => 'Student created successfully',
            'data' => $student
        ]);
    }

    // PATCH /api/students/{id}
    public function update(int $id): void
    {
        $data = $this->readJsonBody();

        $errors = StudentValidator::validate(
            $data,
            partial: true
        );

        if (!empty($errors)) {
            JsonResponse::send(422, [
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors
            ]);
        }

        try {
            $student = $this->studentService->updateStudent($id, $data);
        } catch (PDOException $e) {
            if ((string) $e->getCode() === '23505') {
                JsonResponse::send(409, [
                    'success' => false,
                    'message' => 'Student email already exists'
                ]);
            }

            throw $e;
        }
        if ($student === null) {
            JsonResponse::send(404, [
                'success' => false,
                'message' => 'Student not found'
            ]);
        }

        JsonResponse::send(200, [
            'success' => true,
            'message' => 'Student updated successfully',
            'data' => $student
        ]);
    }

    // DELETE /api/students/{id}
    public function destroy(int $id): void
    {
        $student = $this->studentService->deleteStudent($id);
        if ($student === null) {
            JsonResponse::send(404, [
                'success' => false,
                'message' => 'Student not found'
            ]);
        }
        JsonResponse::send(200, [
            'success' => true,
            'message' => 'Student deleted successfully'
        ]);
    }

    // Shared helper for POST and PATCH
    private function readJsonBody(): array
    {
        $raw = file_get_contents('php://input');

        try {
            $decoded = json_decode(
                $raw,
                false,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException $e) {
            JsonResponse::send(400, [
                'success' => false,
                'message' => 'Malformed JSON request body'
            ]);
        }

        if (!is_object($decoded)) {
            JsonResponse::send(400, [
                'success' => false,
                'message' => 'Request body must be a JSON object'
            ]);
        }

        return get_object_vars($decoded);
    }
}
