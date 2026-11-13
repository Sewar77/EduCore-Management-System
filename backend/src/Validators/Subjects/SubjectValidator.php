<?php
declare(strict_types=1);

namespace App\Validators\Subjects;

final class SubjectValidator
{
    public static function validate(array $data): array
    {
        $errors = [];

        $allowedFields = [
            'name',
            'code',
            'description'
        ];

        foreach ($data as $field => $value) {
            if (!in_array($field, $allowedFields, true)) {
                $errors[$field] = 'Field is not allowed';
            }
        }

        if (
            !isset($data['name']) ||
            !is_string($data['name']) ||
            trim($data['name']) === '' ||
            mb_strlen($data['name']) > 100
        ) {
            $errors['name'] =
                'Name is required and must not exceed 100 characters';
        }

        if (array_key_exists('code', $data)) {
            if (
                $data['code'] !== null &&
                (
                    !is_string($data['code']) ||
                    trim($data['code']) === '' ||
                    strlen($data['code']) > 20
                )
            ) {
                $errors['code'] = 'Invalid subject code';
            }
        }

        if (array_key_exists('description', $data)) {
            if (
                $data['description'] !== null &&
                !is_string($data['description'])
            ) {
                $errors['description'] =
                    'Description must be a string or null';
            }
        }

        return $errors;
    }
}
