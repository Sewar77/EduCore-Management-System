<?php
declare(strict_types=1);

namespace App\Database;

use PDO;
//PHP Data Objects
use RuntimeException;

final class Connection
{
    public static function make(): PDO
    {
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $port = getenv('DB_PORT') ?: '5432';
        $database = getenv('DB_NAME');
        $username = getenv('DB_USER') ?: 'postgres';
        $password = getenv('DB_PASSWORD');
        if (!$database) {
            throw new RuntimeException("data base is not cingifuered!");
        }

        $dsn = "pgsql:host=$host;port=$port;dbname=$database";
        //date source name
        return new PDO(
            $dsn,
            $username,
            $password === false ? "" : $password,
            [
                    // Associative Array
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // error in db or database
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }
}

