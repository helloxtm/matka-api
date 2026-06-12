<?php
/**
 * Copy this file to config.php and fill in your values.
 */
return [
    // MAPI base (no trailing slash)
    'mapi_base_url' => 'https://www.matkaapi.com',

    // From Dashboard → Domains → unique_key (domain_key)
    'domain_key' => 'YOUR_DOMAIN_KEY_HERE',

    // Your website domain (without http/www). Leave empty to auto-detect from request.
    'domain' => '',

    // Request timeout seconds
    'timeout' => 30,

    // When true, cron scripts call db_save.php in each folder (you must create it from db_save.example.php)
    'save_to_database' => false,
];
