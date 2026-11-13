<?php
declare(strict_types=1);

namespace App\Validators\Teachers;

final class TeacherValidator
{
    public static function validate(
        array $data,
        bool $partial = false
    ): array {
        $errors = [];

        $allowed = [
            'fullName',
            'email',
            'phone',
            'status',
            'qualification',
            'hireDate',
            'subjectIds'
        ];

        $required = [
            'fullName',
            'email',
            'phone',
            'subjectIds'
        ];

        foreach ($data as $field => $value) {
            if (!in_array($field, $allowed, true)) {
                $errors[$field] = 'Field is not allowed';
            }
        }

        if (!$partial) {
            foreach ($required as $field) {
                if (!array_key_exists($field, $data)) {
                    $errors[$field] = 'Field is required';
                }
            }
        }

        if ($partial && $data === []) {
            $errors['body'] = 'Provide at least one field';
        }

        if (array_key_exists('fullName', $data)) {
            if (
                !is_string($data['fullName']) ||
                mb_strlen(trim($data['fullName'])) < 2 ||
                mb_strlen($data['fullName']) > 120
            ) {
                $errors['fullName'] = 'Name must be 2-120 characters';
            }
        }

        if (array_key_exists('email', $data)) {
            if (
                !is_string($data['email']) ||
                strlen($data['email']) > 254 ||
                !filter_var($data['email'], FILTER_VALIDATE_EMAIL)
            ) {
                $errors['email'] = 'Invalid email address';
            }
        }

        if (array_key_exists('phone', $data)) {
            if (
                !is_string($data['phone']) ||
                strlen(trim($data['phone'])) < 6 ||
                strlen($data['phone']) > 30
            ) {
                $errors['phone'] = 'Phone must be 6-30 characters';
            }
        }

        if (array_key_exists('status', $data)) {
            if (
                !is_string($data['status']) ||
                !in_array(
                    $data['status'],
                    ['active', 'inactive'],
                    true
                )
            ) {
                $errors['status'] = 'Invalid teacher status';
            }
        }

        if (array_key_exists('qualification', $data)) {
            $value = $data['qualification'];

            if (
                $value !== null &&
                (
                    !is_string($value) ||
                    mb_strlen($value) > 150
                )
            ) {
                $errors['qualification'] = 'Invalid qualification';
            }
        }

        if (array_key_exists('hireDate', $data)) {
            $date = $data['hireDate'];

            if ($date !== null) {
                $validDate = false;

                if (
                    is_string($date) &&
                    preg_match(
                        '/^(\d{4})-(\d{2})-(\d{2})$/',
                        $date,
                        $matches
                    ) === 1
                ) {
                    $validDate = checkdate(
                        (int) $matches[2],
                        (int) $matches[3],
                        (int) $matches[1]
                    );
                }

                if (!$validDate) {
                    $errors['hireDate'] = 'Use YYYY-MM-DD';
                }
            }
        }

        if (array_key_exists('subjectIds', $data)) {
            $ids = $data['subjectIds'];

            if (
                !is_array($ids) ||
                !array_is_list($ids) ||
                count($ids) === 0
            ) {
                $errors['subjectIds'] =
                    'Select at least one subject';
            } else {
                foreach ($ids as $subjectId) {
                    if (!is_int($subjectId) || $subjectId <= 0) {
                        $errors['subjectIds'] =
                            'Subject IDs must be positive integers';
                        break;
                    }
                }

                if (
                    !isset($errors['subjectIds']) &&
                    count($ids) !== count(array_unique($ids))
                ) {
                    $errors['subjectIds'] =
                        'Duplicate subjects are not allowed';
                }
            }
        }

        return $errors;
    }
}
