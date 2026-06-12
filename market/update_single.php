<?php
/**
 * Optional: single market today.
 * ?market=mainbazar
 */
declare(strict_types=1);

require_once __DIR__ . '/../lib/bootstrap.php';

$market = trim((string) ($_GET['market'] ?? 'mainbazar'));
if ($market === '') {
    CronResponse::fail('market param required');
}

require_once __DIR__ . '/../lib/cron_runner.php';

mapi_cron_run(
    'market_api.php',
    ['market' => $market],
    __DIR__,
    'market_save_today'
);
