<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Serve the RiverWind redesign as the public landing site while preserving Laravel routes.
$riverwindRoot = __DIR__ . '/riverwind-bank-redesign';
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$riverwindFile = null;

if ($requestPath === '/') {
    $riverwindFile = $riverwindRoot . '/index.html';
} elseif (preg_match('#^/([a-z0-9-]+\.html)$#i', $requestPath, $matches)) {
    $riverwindFile = $riverwindRoot . '/' . $matches[1];
} elseif (preg_match('#^/assets/(styles\.css|app\.js)$#i', $requestPath, $matches)) {
    $riverwindFile = $riverwindRoot . '/assets/' . $matches[1];
}

if ($riverwindFile && is_file($riverwindFile)) {
    $contentTypes = [
        '.css' => 'text/css; charset=UTF-8',
        '.html' => 'text/html; charset=UTF-8',
        '.js' => 'application/javascript; charset=UTF-8',
    ];
    $extension = strtolower(pathinfo($riverwindFile, PATHINFO_EXTENSION));

    header('Content-Type: ' . ($contentTypes['.' . $extension] ?? 'text/plain; charset=UTF-8'));
    readfile($riverwindFile);
    exit;
}

// Let the root front controller serve the existing Laravel/theme assets correctly.
$staticFile = __DIR__ . str_replace('/', DIRECTORY_SEPARATOR, rawurldecode($requestPath));
$staticExtension = strtolower(pathinfo($staticFile, PATHINFO_EXTENSION));
$staticTypes = [
    'css' => 'text/css; charset=UTF-8',
    'gif' => 'image/gif',
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'js' => 'application/javascript; charset=UTF-8',
    'png' => 'image/png',
    'svg' => 'image/svg+xml',
    'webp' => 'image/webp',
    'woff' => 'font/woff',
    'woff2' => 'font/woff2',
];

if ($requestPath === '/assets/themes/primary/css/color.php' && is_file($staticFile)) {
    require $staticFile;
    exit;
}

if ($staticExtension !== 'php' && isset($staticTypes[$staticExtension]) && is_file($staticFile)) {
    header('Content-Type: ' . $staticTypes[$staticExtension]);
    readfile($staticFile);
    exit;
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/main/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/main/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/main/bootstrap/app.php')
    ->handleRequest(Request::capture());
