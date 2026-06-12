<?php
/**
 * COPY to db_save.php and edit for your database.
 * Enable in config.php: 'save_to_database' => true
 *
 * Requires database/config.php (copy from database/config.example.php)
 */

function market_db(): PDO
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

/** Save today's all-market response from update_today.php */
function market_save_today(array $mapiResponse): bool
{
    $rows = $mapiResponse['data'] ?? [];
    if (!is_array($rows)) {
        return false;
    }

    $pdo = market_db();
    $stmt = $pdo->prepare(
        'INSERT INTO market_results (name, result, result_date, open_time, close_time)
         VALUES (:name, :result, :result_date, :open_time, :close_time)
         ON DUPLICATE KEY UPDATE result = VALUES(result), open_time = VALUES(open_time), close_time = VALUES(close_time)'
    );

    foreach ($rows as $row) {
        if (!is_array($row)) {
            continue;
        }
        $stmt->execute([
            ':name' => (string) ($row['name'] ?? ''),
            ':result' => (string) ($row['result'] ?? ''),
            ':result_date' => (string) ($row['date'] ?? ($mapiResponse['date'] ?? date('Y-m-d'))),
            ':open_time' => (string) ($row['open_time'] ?? ''),
            ':close_time' => (string) ($row['close_time'] ?? ''),
        ]);
    }

    return true;
}

/** Save market list from update_list.php */
function market_save_list(array $mapiResponse): bool
{
    $rows = $mapiResponse['data'] ?? [];
    if (!is_array($rows)) {
        return false;
    }

    $pdo = market_db();
    $stmt = $pdo->prepare(
        'INSERT INTO market_list (name, open_time, close_time, sat_day, sun_day, bg_yellow)
         VALUES (:name, :open_time, :close_time, :sat_day, :sun_day, :bg_yellow)
         ON DUPLICATE KEY UPDATE open_time = VALUES(open_time), close_time = VALUES(close_time)'
    );

    foreach ($rows as $row) {
        if (!is_array($row)) {
            continue;
        }
        $stmt->execute([
            ':name' => (string) ($row['name'] ?? ''),
            ':open_time' => (string) ($row['open_time'] ?? ''),
            ':close_time' => (string) ($row['close_time'] ?? ''),
            ':sat_day' => (string) ($row['sat_day'] ?? '0'),
            ':sun_day' => (string) ($row['sun_day'] ?? '0'),
            ':bg_yellow' => (string) ($row['bg_yellow'] ?? '0'),
        ]);
    }

    return true;
}
