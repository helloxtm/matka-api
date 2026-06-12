<?php
declare(strict_types=1);

final class CronResponse
{
    public static function json(array $payload): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
            header('Access-Control-Allow-Origin: *');
        }
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public static function fail(string $message, array $extra = []): void
    {
        self::json(array_merge([
            'status' => false,
            'message' => $message,
        ], $extra));
    }

    public static function ok(string $message, array $extra = []): void
    {
        self::json(array_merge([
            'status' => true,
            'message' => $message,
        ], $extra));
    }
}
