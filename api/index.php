<?php

error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

if (isset($_SERVER['VERCEL']) || isset($_ENV['VERCEL'])) {
    $forwardedProto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '';
    $forwardedHost = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? '';

    if ($forwardedProto === 'https' || isset($_SERVER['VERCEL'])) {
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_PORT'] = '443';
    }

    if ($forwardedHost !== '') {
        $_SERVER['HTTP_HOST'] = $forwardedHost;
    }

    if ($forwardedHost !== '') {
        $_ENV['APP_URL'] = 'https://'.$forwardedHost;
        $_SERVER['APP_URL'] = 'https://'.$forwardedHost;
    }

    $storagePath = '/tmp/ucc-storage';
    $_SERVER['LARAVEL_STORAGE_PATH'] = $storagePath;
    $_ENV['LARAVEL_STORAGE_PATH'] = $storagePath;

    foreach ([
        'APP_KEY',
        'APP_ENV',
        'APP_DEBUG',
        'DB_CONNECTION',
        'DB_HOST',
        'DB_PORT',
        'DB_DATABASE',
        'DB_USERNAME',
        'DB_PASSWORD',
        'TIDB_CA_CERT',
    ] as $key) {
        $value = $_SERVER[$key] ?? null;
        if ($value === null || $value === '') {
            $value = $_ENV[$key] ?? null;
        }
        if ($value === null || $value === '') {
            $value = getenv($key);
        }
        if ($value !== false && $value !== null && $value !== '') {
            $_SERVER[$key] = $value;
            $_ENV[$key] = $value;
        }
    }

    if (trim((string) ($_ENV['DB_PASSWORD'] ?? '')) === '') {
        throw new RuntimeException('DB_PASSWORD is missing in Vercel Environment Variables.');
    }

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
    } elseif (is_file('/etc/ssl/certs/ca-certificates.crt')) {
        $_ENV['MYSQL_ATTR_SSL_CA'] = '/etc/ssl/certs/ca-certificates.crt';
        $_SERVER['MYSQL_ATTR_SSL_CA'] = '/etc/ssl/certs/ca-certificates.crt';
    } elseif (is_file('/etc/ssl/cert.pem')) {
        $_ENV['MYSQL_ATTR_SSL_CA'] = '/etc/ssl/cert.pem';
        $_SERVER['MYSQL_ATTR_SSL_CA'] = '/etc/ssl/cert.pem';
    } else {
        throw new RuntimeException('TiDB requires TLS. Set TIDB_CA_CERT in Vercel Environment Variables.');
    }
}

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../public/index.php';
