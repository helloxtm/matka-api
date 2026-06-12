<?php
/**
 * COPY to db_save.php — starline / fatafat results
 */

function starline_db(): PDO
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

function starline_save_today(array $mapiResponse): bool
{
    $rows = $mapiResponse['data'] ?? [];
    if (!is_array($rows)) {
        return false;
    }

    $pdo = starline_db();
    $date = (string) ($mapiResponse['date'] ?? date('Y-m-d'));

    $stmt = $pdo->prepare(
        'INSERT INTO starline_results (game, slot, result_time, patti, sd, result, result_date)
         VALUES (:game, :slot, :result_time, :patti, :sd, :result, :result_date)
         ON DUPLICATE KEY UPDATE patti = VALUES(patti), sd = VALUES(sd), result = VALUES(result), result_time = VALUES(result_time)'
    );

    foreach ($rows as $row) {
        if (!is_array($row)) {
            continue;
        }
        $stmt->execute([
            ':game' => (string) ($row['game'] ?? ($mapiResponse['game'] ?? '')),
            ':slot' => (int) ($row['slot'] ?? 0),
            ':result_time' => (string) ($row['time'] ?? ''),
            ':patti' => (string) ($row['patti'] ?? ''),
            ':sd' => (string) ($row['sd'] ?? ''),
            ':result' => (string) ($row['result'] ?? ''),
            ':result_date' => $date,
        ]);
    }

    return true;
}

function starline_save_list(array $mapiResponse): bool
{
    $rows = $mapiResponse['data'] ?? [];
    if (!is_array($rows)) {
        return false;
    }

    $pdo = starline_db();
    $stmt = $pdo->prepare(
        'INSERT INTO starline_games (name) VALUES (:name)
         ON DUPLICATE KEY UPDATE name = VALUES(name)'
    );

    foreach ($rows as $row) {
        if (!is_array($row)) {
            continue;
        }
        $name = (string) ($row['name'] ?? '');
        if ($name === '') {
            continue;
        }
        $stmt->execute([':name' => $name]);
    }

    return true;
}
