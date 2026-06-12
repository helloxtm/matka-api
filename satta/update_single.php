<?php
/**
 * Single satta game today. ?game=GALI
 */
declare(strict_types=1);

require_once __DIR__ . '/../lib/bootstrap.php';

$game = trim((string) ($_GET['game'] ?? 'GALI'));
if ($game === '') {
    CronResponse::fail('game param required');
}

require_once __DIR__ . '/../lib/cron_runner.php';

mapi_cron_run(
    'satta_api.php',
    ['game' => $game],
    __DIR__,
    'satta_save_today'
);
