<?php
declare(strict_types=1);

namespace App\Repositories\Teachers;

use PDO;
use PDOStatement;
use InvalidArgumentException;

final class TeacherRepository
{
    public function __construct(
        private PDO $pdo
    ) {
    }

    private const SELECT_WITH_SUBJECTS = <<<'SQL'
        SELECT
            t.id,
            t.full_name,
            t.email,
            t.phone,
            t.status,
            t.qualification,
            t.hire_date,
            t.created_at,
            t.updated_at,
            COALESCE(
                (
                    SELECT json_agg(
                        json_build_object(
                            'id', s.id,
                            'name', s.name,
                            'code', s.code
                        )
                        ORDER BY s.name, s.id
                    )
                    FROM teacher_subjects ts
                    JOIN subjects s
                        ON s.id = ts.subject_id
                    WHERE ts.teacher_id = t.id
                ),
                '[]'::json
            ) AS subjects
        FROM teachers t
    SQL;

    public function all(): array
    {
        $sql = self::SELECT_WITH_SUBJECTS .
            ' ORDER BY t.id ASC';

        $stmt = $this->pdo->query($sql);

        $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn(array $teacher) => $this->decodeSubjects($teacher),
            $teachers
        );
    }

    public function byTeacherId(int $id): ?array
    {
        $sql = self::SELECT_WITH_SUBJECTS .
            ' WHERE t.id = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

        return $teacher === false
            ? null
            : $this->decodeSubjects($teacher);
    }

    public function createTeacher(array $data): array
    {
        $sql = "
            INSERT INTO teachers (
                full_name,
                email,
                phone,
                status,
                qualification,
                hire_date,
                created_at,
                updated_at
            )
            VALUES (
                :fullName,
                :email,
                :phone,
                :status,
                :qualification,
                :hireDate,
                NOW(),
                NOW()
            )
            RETURNING *
        ";

        $stmt = $this->pdo->prepare($sql);

        $values = [
            'fullName' => $data['fullName'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'status' => $data['status'] ?? 'active',
            'qualification' => $data['qualification'] ?? null,
            'hireDate' => $data['hireDate'] ?? null
        ];

        foreach ($values as $key => $value) {
            $this->bind($stmt, ':' . $key, $value);
        }
        $stmt->execute();
        $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($teacher === false) {
            throw new \RuntimeException(
                'Failed to create teacher'
            );
        }

        return $teacher;
    }

    public function updateDetails(int $id, array $data): void
    {
        $allowedFields = [
            'fullName' => 'full_name',
            'email' => 'email',
            'phone' => 'phone',
            'status' => 'status',
            'qualification' => 'qualification',
            'hireDate' => 'hire_date'
        ];

        if ($data === []) {
            return;
        }

        $setParts = [];
        $params = [];

        foreach ($data as $field => $value) {
            if (!array_key_exists($field, $allowedFields)) {
                throw new InvalidArgumentException(
                    "Invalid field: $field"
                );
            }

            $column = $allowedFields[$field];

            $setParts[] = "$column = :$column";
            $params[$column] = $value;
        }

        $sql = "
            UPDATE teachers
            SET " . implode(', ', $setParts) . ",
                updated_at = NOW()
            WHERE id = :id
        ";

        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $column => $value) {
            $this->bind($stmt, ':' . $column, $value);
        }

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function lockById(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT id FROM teachers WHERE id = :id FOR UPDATE'
        );

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }

    public function replaceSubjects(
        int $teacherId,
        array $subjectIds
    ): void {
        $delete = $this->pdo->prepare(
            'DELETE FROM teacher_subjects WHERE teacher_id = :teacherId'
        );

        $delete->bindValue(
            ':teacherId',
            $teacherId,
            PDO::PARAM_INT
        );
        $delete->execute();

        $insert = $this->pdo->prepare("
            INSERT INTO teacher_subjects (
                teacher_id,
                subject_id
            )
            VALUES (:teacherId, :subjectId)
        ");

        foreach ($subjectIds as $subjectId) {
            $insert->bindValue(
                ':teacherId',
                $teacherId,
                PDO::PARAM_INT
            );
            $insert->bindValue(
                ':subjectId',
                $subjectId,
                PDO::PARAM_INT
            );

            $insert->execute();
        }
    }

    public function deleteTeacher(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'DELETE FROM teachers WHERE id = :id RETURNING *'
        );

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

        return $teacher ?: null;
    }

    private function decodeSubjects(array $teacher): array
    {
        $teacher['subjects'] = json_decode(
            $teacher['subjects'],
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        return $teacher;
    }

    private function bind(
        PDOStatement $stmt,
        string $placeholder,
        mixed $value
    ): void {
        $type = match (true) {
            $value === null => PDO::PARAM_NULL,
            is_int($value) => PDO::PARAM_INT,
            default => PDO::PARAM_STR
        };

        $stmt->bindValue($placeholder, $value, $type);
    }
}
