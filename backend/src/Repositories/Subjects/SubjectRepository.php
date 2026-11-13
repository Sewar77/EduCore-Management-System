<?php
declare(strict_types=1);

namespace App\Repositories\Subjects;

use PDO;

final class SubjectRepository
{
    public function __construct(
        private PDO $pdo
    ) {
    }

    public function all(): array
    {
        $sql = "
            SELECT id, name, code
            FROM subjects
            ORDER BY name ASC
        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): array
    {
        $sql = "
        INSERT INTO subjects (
            name,
            code,
            description,
            created_at,
            updated_at
        )
        VALUES (
            :name,
            :code,
            :description,
            NOW(),
            NOW()
        )
        RETURNING *
    ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(
            ':name',
            trim($data['name']),
            PDO::PARAM_STR
        );

        $code = $data['code'] ?? null;

        $stmt->bindValue(
            ':code',
            $code,
            $code === null
            ? PDO::PARAM_NULL
            : PDO::PARAM_STR
        );

        $description = $data['description'] ?? null;

        $stmt->bindValue(
            ':description',
            $description,
            $description === null
            ? PDO::PARAM_NULL
            : PDO::PARAM_STR
        );

        $stmt->execute();

        $subject = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($subject === false) {
            throw new \RuntimeException(
                'Failed to create subject'
            );
        }

        return $subject;
    }

}
