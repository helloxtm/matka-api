<?php
/**
 * COPY to db_save.php — satta / disawar results
 */

function satta_db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $cfg = require dirname(__DIR__) . '/database/config.php';
        $pdo = new PDO(
            'mysql:host=' . $cfg['host'] . ';dbname=' . $cfg['name'] . ';charset=utf8mb4',
            $cfg['user'],
            $cfg['pass'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
    return $pdo;
}

function satta_parse_date(string $raw, string $fallback): string
{
    if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $raw)) {
        $parts = explode('-', $raw);
        return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
    }
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw)) {
        return $raw;
    }
    return $fallback;
}

function satta_save_today(array $mapiResponse): bool
{
    $rows = $mapiResponse['data'] ?? [];
    if (!is_array($rows)) {
        return false;
    }

    $pdo = satta_db();
    $defaultDate = (string) ($mapiResponse['date'] ?? date('Y-m-d'));

    $stmt = $pdo->prepare(
        'INSERT INTO satta_results (name, result, result_date, result_time)
         VALUES (:name, :result, :result_date, :result_time)
         ON DUPLICATE KEY UPDATE result = VALUES(result), result_time = VALUES(result_time)'
    );

    foreach ($rows as $row) {
        if (!is_array($row)) {
            continue;
        }
        $date = isset($row['date'])
            ? satta_parse_date((string) $row['date'], $defaultDate)
            : $defaultDate;

        $stmt->execute([
            ':name' => (string) ($row['name'] ?? ($mapiResponse['game'] ?? '')),
            ':result' => (string) ($row['result'] ?? ''),
            ':result_date' => $date,
            ':result_time' => (string) ($row['time'] ?? ''),
        ]);
    }

    return true;
}

function satta_save_list(array $mapiResponse): bool
{
    $rows = $mapiResponse['data'] ?? [];
    if (!is_array($rows)) {
        return false;
    }

    $pdo = satta_db();
    $stmt = $pdo->prepare(
        'INSERT INTO satta_list (name, result_time, bg_yellow)
         VALUES (:name, :result_time, :bg_yellow)
         ON DUPLICATE KEY UPDATE result_time = VALUES(result_time), bg_yellow = VALUES(bg_yellow)'
    );

    foreach ($rows as $row) {
        if (!is_array($row)) {
            continue;
        }
        $stmt->execute([
            ':name' => (string) ($row['name'] ?? ''),
            ':result_time' => (string) ($row['time'] ?? ''),
            ':bg_yellow' => (string) ($row['bg_yellow'] ?? '0'),
        ]);
    }

    return true;
}
