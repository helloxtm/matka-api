<?php
/**
 * Cron: fetch all market results for today.
 * URL: https://yourdomain.com/matka-api/market/update_today.php
 */
declare(strict_types=1);

require_once __DIR__ . '/../lib/cron_runner.php';

mapi_cron_run(
    'market_api.php',
    ['market' => 'all'],
    __DIR__,
    'market_save_today'
);
