<?php
declare(strict_types=1);
namespace App\Repositories\Logs;
use PDO;
final class ActivityLogRepository
{
    public function __construct(
        private PDO $pdo
    ) {
    }
    public function create(
        string $actionType,
        string $entityType,
        int $recordId,
        string $description
    ): void {
        $sql = "
            INSERT INTO activity_logs (
                action_type,
                entity_type,
                record_id,
                description,
                created_at
            )
            VALUES (
                :actionType,
                :entityType,
                :recordId,
                :description,
                NOW()
            )
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(
            ':actionType',
            $actionType,
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':entityType',
            $entityType,
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':recordId',
            $recordId,
            PDO::PARAM_INT
        );
        $stmt->bindValue(
            ':description',
            $description,
            PDO::PARAM_STR
        );
        $stmt->execute();
    }
}