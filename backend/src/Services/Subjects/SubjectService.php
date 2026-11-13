<?php
declare(strict_types=1);

namespace App\Services\Subjects;

use App\Repositories\Subjects\SubjectRepository;
use App\Repositories\Logs\ActivityLogRepository;
use PDO;
use Throwable;

final class SubjectService
{
    public function __construct(
        private PDO $pdo,
        private SubjectRepository $subjects,
        private ActivityLogRepository $logs
    ) {
    }

    public function createSubject(array $data): array
    {
        $this->pdo->beginTransaction();

        try {
            // 1. Create subject
            $subject = $this->subjects->create($data);

            // 2. Record activity
            $this->logs->create(
                'ADD',
                'SUBJECT',
                (int) $subject['id'],
                'Created subject: ' . $subject['name']
            );

            // 3. Save both changes
            $this->pdo->commit();

            return $subject;

        } catch (Throwable $e) {

            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $e;
        }
    }
}
