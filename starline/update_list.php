<?php
/**
 * Cron: starline game list.
 */
declare(strict_types=1);

require_once __DIR__ . '/../lib/cron_runner.php';

mapi_cron_run(
    'starline_api.php',
    ['game_list' => '1'],
    __DIR__,
    'starline_save_list'
);
