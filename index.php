<?php
/**
 * Quick MAPI connection test (delete or protect after setup).
 */
declare(strict_types=1);

require_once __DIR__ . '/lib/bootstrap.php';

$client = new MapiClient($config);
$test = $client->get('market_api.php', ['market_list' => '1']);

header('Content-Type: application/json; charset=utf-8');

echo json_encode([
    'status' => true,
    'message' => 'Matka API client — connection test',
    'domain_detected' => $client->resolveDomain(),
    'domain_key_set' => $client->getDomainKey() !== '' && $client->getDomainKey() !== 'YOUR_DOMAIN_KEY_HERE',
    'mapi_test' => $test,
    'cron_urls' => [
        'market_today' => 'market/update_today.php',
        'market_list' => 'market/update_list.php',
        'starline_today' => 'starline/update_today.php',
        'starline_list' => 'starline/update_list.php',
        'satta_today' => 'satta/update_today.php',
        'satta_list' => 'satta/update_list.php',
    ],
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
