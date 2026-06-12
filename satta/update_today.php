<?php
/**
 * Cron: all satta games — today.
 */
declare(strict_types=1);

require_once __DIR__ . '/../lib/cron_runner.php';

mapi_cron_run(
    'satta_api.php',
    ['game' => 'all'],
    __DIR__,
    'satta_save_today'
);
