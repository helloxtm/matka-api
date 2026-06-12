<?php
/**
 * Cron: fetch market list (names + times).
 * URL: https://yourdomain.com/matka-api/market/update_list.php
 */
declare(strict_types=1);

require_once __DIR__ . '/../lib/cron_runner.php';

mapi_cron_run(
    'market_api.php',
    ['market_list' => '1'],
    __DIR__,
    'market_save_list'
);
