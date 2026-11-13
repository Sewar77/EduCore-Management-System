<?php
declare(strict_types=1);

namespace App\Services\Students;

use App\Repositories\Students\StudentRepository;
use App\Repositories\Logs\ActivityLogRepository;
use PDO;
use Throwable;

final class StudentService
{
    public function __construct(
        private PDO $pdo,
        private StudentRepository $students,
        private ActivityLogRepository $logs
    ) {
    }

    public function createStudent(array $data): array
    {
        $this->pdo->beginTransaction();

        try {
            $student = $this->students->createStudent($data);

            $this->logs->create(
                'ADD',
                'STUDENT',
                (int) $student['id'],
                'Created student: ' . $student['full_name']
            );

            $this->pdo->commit();

            return $student;

        } catch (Throwable $e) {
            // throwable catch more types of errors. 
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $e;
        }
    }

    public function updateStudent(int $id, array $data): ?array
    {
        $this->pdo->beginTransaction();

        try {
            $student = $this->students->updateStudent(
                $id,
                $data
            );

            if ($student === null) {
                $this->pdo->rollBack();
                return null;
            }

            $this->logs->create(
                'UPDATE',
                'STUDENT',
                $id,
                'Updated student: ' . $student['full_name']
            );

            $this->pdo->commit();

            return $student;

        } catch (Throwable $e) {

            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $e;
        }
    }

    public function deleteStudent(int $id): ?array
    {
        $this->pdo->beginTransaction();

        try {
            $student = $this->students->deleteStudent($id);

            if ($student === null) {
                $this->pdo->rollBack();
                return null;
            }

            $this->logs->create(
                'DELETE',
                'STUDENT',
                $id,
                'Deleted student: ' . $student['full_name']
            );

            $this->pdo->commit();

            return $student;

        } catch (Throwable $e) {

            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $e;
        }
    }
}
