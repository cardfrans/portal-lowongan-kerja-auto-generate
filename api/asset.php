<?php

$requestedPath = $_GET['path'] ?? '';

if (! is_string($requestedPath) || $requestedPath === '' || str_contains($requestedPath, '..')) {
    http_response_code(404);
    exit;
}

$assetPath = __DIR__.'/../public/build/'.ltrim($requestedPath, '/');

if (! is_file($assetPath)) {
    http_response_code(404);
    exit;
}

$mimeType = match (strtolower(pathinfo($assetPath, PATHINFO_EXTENSION))) {
    'css' => 'text/css; charset=UTF-8',
    'js' => 'application/javascript; charset=UTF-8',
    'json' => 'application/json; charset=UTF-8',
    'map' => 'application/json; charset=UTF-8',
    default => 'application/octet-stream',
};

header('Content-Type: '.$mimeType);
header('Cache-Control: public, max-age=31536000, immutable');
readfile($assetPath);
