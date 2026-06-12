<?php
/**
 * Single game all slots today. ?game=kalyanstarline
 */
declare(strict_types=1);

require_once __DIR__ . '/../lib/bootstrap.php';

$game = trim((string) ($_GET['game'] ?? 'kalyanstarline'));
if ($game === '') {
    CronResponse::fail('game param required');
}

require_once __DIR__ . '/../lib/cron_runner.php';

mapi_cron_run(
    'starline_api.php',
    ['game' => $game],
    __DIR__,
    'starline_save_today'
);
