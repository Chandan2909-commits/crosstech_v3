<?php

/**
 * Vercel Serverless Entry Point — CrossFlow CRM
 *
 * The vercel-php runtime finds api/composer.json and installs vendor/
 * here (--no-dev, ~84MB vs 128MB with dev deps).
 * We load that autoloader then boot Laravel from democrm/Files/core/.
 */

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Autoloader installed by vercel-php runtime into api/vendor/
require __DIR__ . '/vendor/autoload.php';

// Boot Laravel
(require_once __DIR__ . '/../democrm/Files/core/bootstrap/app.php')
    ->handleRequest(Request::capture());
