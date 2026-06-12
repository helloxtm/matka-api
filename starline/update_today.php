<?php
/**
 * Cron: all starline games — today results.
 */
declare(strict_types=1);

require_once __DIR__ . '/../lib/cron_runner.php';

mapi_cron_run(
    'starline_api.php',
    ['game' => 'all'],
    __DIR__,
    'starline_save_today'
);
