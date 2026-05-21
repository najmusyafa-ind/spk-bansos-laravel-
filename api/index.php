<?php
// ===== STEP 1: Force DB credentials FIRST (before any cache) =====
// This overrides Vercel dashboard env vars that may point to old IPv6 host
putenv('DB_CONNECTION=pgsql');
putenv('DB_HOST=aws-1-ap-southeast-1.pooler.supabase.com');
putenv('DB_PORT=6543');
putenv('DB_DATABASE=postgres');
putenv('DB_USERNAME=postgres.atuwrqyjgfvqbuiqbqix');
$_ENV['DB_CONNECTION'] = $_SERVER['DB_CONNECTION'] = 'pgsql';
$_ENV['DB_HOST'] = $_SERVER['DB_HOST'] = 'aws-1-ap-southeast-1.pooler.supabase.com';
$_ENV['DB_PORT'] = $_SERVER['DB_PORT'] = '6543';
$_ENV['DB_DATABASE'] = $_SERVER['DB_DATABASE'] = 'postgres';
$_ENV['DB_USERNAME'] = $_SERVER['DB_USERNAME'] = 'postgres.atuwrqyjgfvqbuiqbqix';

// ===== STEP 2: Force session and cache drivers =====
putenv('SESSION_DRIVER=database');
$_ENV['SESSION_DRIVER'] = $_SERVER['SESSION_DRIVER'] = 'database';
putenv('CACHE_STORE=array');
$_ENV['CACHE_STORE'] = $_SERVER['CACHE_STORE'] = 'array';

// ===== STEP 3: Set Vercel read-only filesystem workarounds =====
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

// ===== STEP 4: Delete stale config cache (may contain old DB host) =====
@unlink('/tmp/bootstrap/cache/config.php');

// ===== STEP 5: Force HTTPS and create temp directories =====
$_SERVER['HTTPS'] = 'on';
@mkdir('/tmp/storage/framework/views', 0777, true);
@mkdir('/tmp/bootstrap/cache', 0777, true);
@mkdir('/tmp/storage/framework/sessions', 0777, true);

require __DIR__ . '/../public/index.php';
