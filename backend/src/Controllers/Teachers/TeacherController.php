<?php
declare(strict_types=1);

namespace App\Controllers\Teachers;

use App\Repositories\Teachers\TeacherRepository;
use App\Services\Teachers\TeacherService;
use App\Validators\Teachers\TeacherValidator;
use App\Support\JsonResponse;
use PDOException;
use JsonException;

final class TeacherController
{
    public function __construct(
        private TeacherRepository $teachers,
        private TeacherService $teacherService
    ) {
    }

    public function index(): void
    {
        $teachers = $this->teachers->all();

        JsonResponse::send(200, [
            'success' => true,
            'data' => $teachers
        ]);
    }

    public function show(int $id): void
    {
        $teacher = $this->teachers->byTeacherId($id);

        if ($teacher === null) {
            JsonResponse::send(404, [
                'success' => false,
                'message' => 'Teacher not found'
            ]);
        }

        JsonResponse::send(200, [
            'success' => true,
            'data' => $teacher
        ]);
    }

    public function store(): void
    {
        $data = $this->readJsonBody();

        $errors = TeacherValidator::validate($data);

        if ($errors !== []) {
            JsonResponse::send(422, [
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors
            ]);
        }

        try {
            $teacher = $this->teacherService->createTeacher($data);
        } catch (PDOException $e) {
            $this->handleDatabaseConflict($e);
            throw $e;
        }

        JsonResponse::send(201, [
            'success' => true,
            'message' => 'Teacher created successfully',
            'data' => $teacher
        ]);
    }

    public function update(int $id): void
    {
        $data = $this->readJsonBody();

        $errors = TeacherValidator::validate(
            $data,
            partial: true
        );

        if ($errors !== []) {
            JsonResponse::send(422, [
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors
            ]);
        }

        try {
            $teacher = $this->teacherService->updateTeacher(
                $id,
                $data
            );
        } catch (PDOException $e) {
            $this->handleDatabaseConflict($e);
            throw $e;
        }

        if ($teacher === null) {
            JsonResponse::send(404, [
                'success' => false,
                'message' => 'Teacher not found'
            ]);
        }

        JsonResponse::send(200, [
            'success' => true,
            'message' => 'Teacher updated successfully',
            'data' => $teacher
        ]);
    }

    public function destroy(int $id): void
    {
        $teacher = $this->teacherService->deleteTeacher($id);

        if ($teacher === null) {
            JsonResponse::send(404, [
                'success' => false,
                'message' => 'Teacher not found'
            ]);
        }

        JsonResponse::send(200, [
            'success' => true,
            'message' => 'Teacher deleted successfully'
        ]);
    }

    private function readJsonBody(): array
    {
        $raw = file_get_contents('php://input');

        if ($raw === false) {
            JsonResponse::send(400, [
                'success' => false,
                'message' => 'Could not read request body'
            ]);
        }

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
                'message' => 'Malformed JSON body'
            ]);
        }

        if (!is_object($decoded)) {
            JsonResponse::send(400, [
                'success' => false,
                'message' => 'Body must be a JSON object'
            ]);
        }

        return get_object_vars($decoded);
    }

    private function handleDatabaseConflict(
        PDOException $e
    ): void {
        $code = (string) $e->getCode();

        if ($code === '23505') {
            JsonResponse::send(409, [
                'success' => false,
                'message' => 'Teacher email already exists'
            ]);
        }

        if ($code === '23503') {
            JsonResponse::send(422, [
                'success' => false,
                'message' => 'One or more subjects do not exist'
            ]);
        }
    }
}
