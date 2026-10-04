<?php

if (isset($_SERVER['VERCEL']) || isset($_ENV['VERCEL'])) {
    $storagePath = '/tmp/ucc-storage';
    $_SERVER['LARAVEL_STORAGE_PATH'] = $storagePath;
    $_ENV['LARAVEL_STORAGE_PATH'] = $storagePath;

    foreach ([
        $storagePath,
        $storagePath.'/framework/cache',
        $storagePath.'/framework/sessions',
        $storagePath.'/framework/views',
        $storagePath.'/logs',
        $storagePath.'/app/public',
    ] as $directory) {
        if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
            throw new RuntimeException("Unable to create Laravel runtime directory: {$directory}");
        }
    }

    foreach ([
        'APP_CONFIG_CACHE' => $storagePath.'/framework/cache/config.php',
        'APP_EVENTS_CACHE' => $storagePath.'/framework/cache/events.php',
        'APP_PACKAGES_CACHE' => $storagePath.'/framework/cache/packages.php',
        'APP_ROUTES_CACHE' => $storagePath.'/framework/cache/routes-v7.php',
        'APP_SERVICES_CACHE' => $storagePath.'/framework/cache/services.php',
        'VIEW_COMPILED_PATH' => $storagePath.'/framework/views',
    ] as $key => $value) {
        $_SERVER[$key] = $value;
        $_ENV[$key] = $value;
    }

    $tidbCaCertificate = $_ENV['TIDB_CA_CERT'] ?? $_SERVER['TIDB_CA_CERT'] ?? '';
    if ($tidbCaCertificate !== '') {
        $certificatePath = '/tmp/tidb-ca.pem';
        if (file_put_contents($certificatePath, $tidbCaCertificate) === false) {
            throw new RuntimeException('Unable to write the TiDB CA certificate.');
        }

        $_ENV['MYSQL_ATTR_SSL_CA'] = $certificatePath;
        $_SERVER['MYSQL_ATTR_SSL_CA'] = $certificatePath;
    }
}

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../public/index.php';
