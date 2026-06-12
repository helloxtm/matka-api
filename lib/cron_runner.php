<?php
declare(strict_types=1);

/**
 * Shared cron helper — fetch MAPI, optional db_save, JSON output.
 *
 * @param string $endpoint e.g. market_api.php
 * @param array<string, scalar|null> $params
 * @param string $moduleFolder absolute path to market|starline|satta folder
 * @param string $saveFunction callable name in db_save.php e.g. market_save_today
 */
function mapi_cron_run(string $endpoint, array $params, string $moduleFolder, string $saveFunction): void
{
    require_once dirname(__DIR__) . '/lib/bootstrap.php';

    /** @var array $config */
    $client = new MapiClient($config);
    $response = $client->get($endpoint, $params);

    if (!($response['status'] ?? false)) {
        CronResponse::fail((string) ($response['message'] ?? 'MAPI error'), [
            'mapi_response' => $response,
        ]);
    }

    $dbSaved = false;
    $dbMessage = '';

    if (!empty($config['save_to_database'])) {
        $dbSaveFile = $moduleFolder . '/db_save.php';
        if (is_file($dbSaveFile)) {
            require_once $dbSaveFile;
            if (function_exists($saveFunction)) {
                try {
                    $dbSaved = (bool) $saveFunction($response);
                    $dbMessage = $dbSaved ? 'Database save completed' : 'Database save returned false';
                } catch (Throwable $e) {
                    $dbMessage = 'Database save error: ' . $e->getMessage();
                }
            } else {
                $dbMessage = 'Function ' . $saveFunction . ' not found in db_save.php';
            }
        } else {
            $dbMessage = 'save_to_database is true but db_save.php missing — copy from db_save.example.php';
        }
    }

    CronResponse::ok('Data fetched from MAPI', [
        'db_saved' => $dbSaved,
        'db_message' => $dbMessage,
        'mapi' => $response,
    ]);
}
