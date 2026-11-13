<?php
declare(strict_types=1);

namespace App\Support;

final class JsonResponse
{
    public static function send(
        int $status,
        array $data
    ): never {
        http_response_code($status);

        header("Content-Type: application/json; charset=utf-8");

        echo json_encode(
            $data,
            JSON_THROW_ON_ERROR
        );

        exit;
    }
}
