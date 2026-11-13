<?php
declare(strict_types=1);

namespace App\Controllers\Subjects;

use App\Repositories\Subjects\SubjectRepository;
use App\Validators\Subjects\SubjectValidator;
use App\Support\JsonResponse;
use App\Services\Subjects\SubjectService;

use PDOException;
use JsonException;

final class SubjectController
{
    public function __construct(
        private SubjectRepository $subjects,
        private SubjectService $subjectService

    ) {
    }

    public function index(): void
    {
        $subjects = $this->subjects->all();

        JsonResponse::send(200, [
            'success' => true,
            'data' => $subjects
        ]);
    }

    public function store(): void
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
                'message' => 'Invalid JSON'
            ]);
        }

        if (!is_object($decoded)) {
            JsonResponse::send(400, [
                'success' => false,
                'message' => 'Body must be a JSON object'
            ]);
        }

        $data = get_object_vars($decoded);

        $errors = SubjectValidator::validate($data);

        if ($errors !== []) {
            JsonResponse::send(422, [
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors
            ]);
        }

        try {
            $subject = $this->subjectService->createSubject($data);
        } catch (PDOException $e) {

            if ((string) $e->getCode() === '23505') {
                JsonResponse::send(409, [
                    'success' => false,
                    'message' => 'Subject name or code already exists'
                ]);
            }

            throw $e;
        }

        JsonResponse::send(201, [
            'success' => true,
            'message' => 'Subject created successfully',
            'data' => $subject
        ]);
    }
}
