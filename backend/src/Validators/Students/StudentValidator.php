<?php
declare(strict_types=1);

namespace App\Validators\Students;
final class StudentValidator
{
    public static function validate(
        array $data,
        bool $partial = false
    ): array {
        $errors = [];
        $allowedFields = [
            'fullName',
            'email',
            'phone',
            'age',
            'gradeClass'
        ];
        $requiredFields = [
            'fullName',
            'email',
            'age',
            'gradeClass'
        ];
        // Reject unknown fields
        foreach ($data as $field => $value) {
            if (!in_array($field, $allowedFields, true)) {
                $errors[$field] = 'Field is not allowed';
            }
        }
        // POST: required fields
        if (!$partial) {
            foreach ($requiredFields as $field) {
                if (!array_key_exists($field, $data)) {
                    $errors[$field] = 'Field is required';
                }
            }
        }
        // PATCH: reject empty request
        if ($partial && empty($data)) {
            $errors['body'] = 'Provide at least one field';
        }
        if (array_key_exists('fullName', $data)) {
            if (
                !is_string($data['fullName']) ||
                mb_strlen(trim($data['fullName'])) < 4 ||
                mb_strlen($data['fullName']) > 120
                // count characters the user sees
            ) {
                $errors['fullName'] = 'Name must be 4-120 characters';
            }
        }

        if (array_key_exists('email', $data)) {
            if (
                !is_string($data['email']) ||
                strlen($data['email']) > 254 ||
                // count bytes,
                !filter_var($data['email'], FILTER_VALIDATE_EMAIL)
            ) {
                $errors['email'] = 'Invalid email address';
            }
        }

        if (array_key_exists('age', $data)) {
            if (
                !is_int($data['age']) ||
                $data['age'] < 1 ||
                $data['age'] > 120
            ) {
                $errors['age'] = 'Age must be between 1 and 120';
            }
        }

        if (array_key_exists('gradeClass', $data)) {
            if (
                !is_string($data['gradeClass']) ||
                trim($data['gradeClass']) === '' ||
                mb_strlen($data['gradeClass']) > 50
            ) {
                $errors['gradeClass'] = 'Invalid grade or class';
            }
        }

        if (array_key_exists('phone', $data)) {
            if (
                $data['phone'] !== null &&
                (
                    !is_string($data['phone']) ||
                    strlen($data['phone']) > 30
                )
            ) {
                $errors['phone'] = 'Invalid phone number';
            }
        }

        return $errors;
    }
}
