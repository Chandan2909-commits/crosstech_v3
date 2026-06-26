<?php

/**
 * Vercel Serverless Entry Point — CrossFlow CRM
 *
 * The vercel-php runtime installs vendor/ here (from api/composer.json).
 * We load that autoloader, then bootstrap Laravel from Files/core/.
 */

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Autoloader installed by vercel-php runtime into api/vendor/
require __DIR__ . '/vendor/autoload.php';

// Boot Laravel from the actual app directory
(require_once __DIR__ . '/../Files/core/bootstrap/app.php')
    ->handleRequest(Request::capture());
