<?php
/**
 * Cron: satta game list.
 */
declare(strict_types=1);

require_once __DIR__ . '/../lib/cron_runner.php';

mapi_cron_run(
    'satta_api.php',
    ['satta_list' => '1'],
    __DIR__,
    'satta_save_list'
);
