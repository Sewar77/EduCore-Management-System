<?php
declare(strict_types=1);

namespace App\Repositories\Students;
use PDO;

final class StudentRepository
{
    public function __construct(
        private PDO $pdo
    ) {
    }

    public function all(): array
    {
        $sql = "
            SELECT
                id,
                full_name,
                email,
                phone,
                age,
                grade_class,
                created_at,
                updated_at
            FROM students
            ORDER BY id ASC
        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // associative arrays.
    }
    public function byStudentId(int $id): ?array
    {
        $sql = "
                SELECT
                id,
                full_name,
                email,
                phone,
                age,
                grade_class,
                created_at,
                updated_at
            FROM students where id=:id
            ORDER BY id ASC ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
        ]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        return $student ?: null;
    }

    public function createStudent(array $data): array
    {
        $sql = "
        INSERT INTO students (
            full_name,
            email,
            phone,
            age,
            grade_class,
            created_at,
            updated_at
        )
        VALUES (
            :fullName,
            :email,
            :phone,
            :age,
            :gradeClass,
            NOW(),
            NOW()
        )
        RETURNING *
    ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':fullName', $data['fullName'], PDO::PARAM_STR);
        $stmt->bindValue(':email', $data['email'], PDO::PARAM_STR);

        $stmt->bindValue(
            ':phone',
            $data['phone'] ?? null,
            isset($data['phone']) ? PDO::PARAM_STR : PDO::PARAM_NULL
        );

        $stmt->bindValue(':age', $data['age'], PDO::PARAM_INT);
        $stmt->bindValue(':gradeClass', $data['gradeClass'], PDO::PARAM_STR);

        $stmt->execute();

        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($student === false) {
            throw new \RuntimeException('Failed to create student');
        }

        return $student;
    }


    public function updateStudent(int $id, array $data): ?array
    {
        // API fields mapped to database columns
        $allowedFields = [
            'fullName' => 'full_name',
            'email' => 'email',
            'phone' => 'phone',
            'age' => 'age',
            'gradeClass' => 'grade_class'
        ];

        $setParts = [];
        $params = [];

        foreach ($data as $field => $value) {

            if (!array_key_exists($field, $allowedFields)) {
                throw new \InvalidArgumentException(
                    "Invalid field: $field"
                );
            }

            $column = $allowedFields[$field];

            $setParts[] = "$column = :$column";
            $params[$column] = $value;
        }

        if (empty($setParts)) {
            throw new \InvalidArgumentException(
                "No fields provided for update"
            );
        }

        $sql = "
        UPDATE students
        SET " . implode(', ', $setParts) . ",
            updated_at = NOW()
        WHERE id = :id
        RETURNING *
    ";

        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $column => $value) {

            $type = $value === null
                ? PDO::PARAM_NULL
                : ($column === 'age'
                    ? PDO::PARAM_INT
                    : PDO::PARAM_STR);

            $stmt->bindValue(":$column", $value, $type);
        }

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        return $student ?: null;
    }
    public function deleteStudent(int $id): ?array
    {
        $sql = 'delete from students where id=:id returning *';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
        ]);
        $students = $stmt->fetch(PDO::FETCH_ASSOC);
        return $students ?: null;
    }
    public function searchStudents(string $search, int $page, int $limit): array
    {
        $offset = ($page - 1) * $limit;

        $sql = "
        SELECT * FROM students
        WHERE full_name ILIKE :search
           OR email       ILIKE :search
           OR phone       ILIKE :search
        ORDER BY full_name             
        LIMIT :limit OFFSET :offset
    ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
