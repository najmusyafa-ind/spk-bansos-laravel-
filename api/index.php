<?php
// Vercel Serverless Fix for Laravel 11 Read-Only Filesystem
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
putenv('APP_CONFIG_CACHE=/tmp/bootstrap/cache/config.php');
putenv('APP_EVENTS_CACHE=/tmp/bootstrap/cache/events.php');
putenv('APP_PACKAGES_CACHE=/tmp/bootstrap/cache/packages.php');
putenv('APP_ROUTES_CACHE=/tmp/bootstrap/cache/routes.php');
putenv('APP_SERVICES_CACHE=/tmp/bootstrap/cache/services.php');
$_ENV['APP_CONFIG_CACHE'] = $_SERVER['APP_CONFIG_CACHE'] = '/tmp/bootstrap/cache/config.php';
$_ENV['APP_EVENTS_CACHE'] = $_SERVER['APP_EVENTS_CACHE'] = '/tmp/bootstrap/cache/events.php';
$_ENV['APP_PACKAGES_CACHE'] = $_SERVER['APP_PACKAGES_CACHE'] = '/tmp/bootstrap/cache/packages.php';
$_ENV['APP_ROUTES_CACHE'] = $_SERVER['APP_ROUTES_CACHE'] = '/tmp/bootstrap/cache/routes.php';
$_ENV['APP_SERVICES_CACHE'] = $_SERVER['APP_SERVICES_CACHE'] = '/tmp/bootstrap/cache/services.php';
$_ENV['VIEW_COMPILED_PATH'] = $_SERVER['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';

// Create temp directories if they don't exist
@mkdir('/tmp/storage/framework/views', 0777, true);
@mkdir('/tmp/bootstrap/cache', 0777, true);

require __DIR__ . '/../public/index.php';
