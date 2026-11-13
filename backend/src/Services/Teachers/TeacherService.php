<?php
declare(strict_types=1);

namespace App\Services\Teachers;

use App\Repositories\Teachers\TeacherRepository;
use App\Repositories\Logs\ActivityLogRepository;
use PDO;
use Throwable;
use RuntimeException;

final class TeacherService
{
    public function __construct(
        private PDO $pdo,
        private TeacherRepository $teachers,
        private ActivityLogRepository $logs
    ) {
    }

    public function createTeacher(array $data): array
    {
        $this->pdo->beginTransaction();

        try {
            $teacher = $this->teachers->createTeacher($data);

            $id = (int) $teacher['id'];

            $this->teachers->replaceSubjects(
                $id,
                $data['subjectIds']
            );

            $teacher = $this->teachers->byTeacherId($id);

            if ($teacher === null) {
                throw new RuntimeException(
                    'Created teacher could not be retrieved'
                );
            }

            $this->logs->create(
                'ADD',
                'TEACHER',
                $id,
                'Created teacher: ' . $teacher['full_name']
            );

            $this->pdo->commit();

            return $teacher;

        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $e;
        }
    }

    public function updateTeacher(
        int $id,
        array $data
    ): ?array {
        $this->pdo->beginTransaction();

        try {
            if (!$this->teachers->lockById($id)) {
                $this->pdo->rollBack();
                return null;
            }

            $details = array_diff_key(
                $data,
                ['subjectIds' => true]
            );

            if ($details !== []) {
                $this->teachers->updateDetails(
                    $id,
                    $details
                );
            }

            if (array_key_exists('subjectIds', $data)) {
                $this->teachers->replaceSubjects(
                    $id,
                    $data['subjectIds']
                );
            }

            $teacher = $this->teachers->byTeacherId($id);

            if ($teacher === null) {
                throw new RuntimeException(
                    'Updated teacher could not be retrieved'
                );
            }

            $this->logs->create(
                'UPDATE',
                'TEACHER',
                $id,
                'Updated teacher: ' . $teacher['full_name']
            );

            $this->pdo->commit();

            return $teacher;

        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $e;
        }
    }

    public function deleteTeacher(int $id): ?array
    {
        $this->pdo->beginTransaction();

        try {
            $teacher = $this->teachers->deleteTeacher($id);

            if ($teacher === null) {
                $this->pdo->rollBack();
                return null;
            }

            $this->logs->create(
                'DELETE',
                'TEACHER',
                $id,
                'Deleted teacher: ' . $teacher['full_name']
            );

            $this->pdo->commit();

            return $teacher;

        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $e;
        }
    }
}
