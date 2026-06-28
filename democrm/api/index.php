<?php

/**
 * Vercel Serverless Entry Point — CrossFlow CRM
 *
 * Uses the full vendor/ from Files/core/ — no separate api/vendor needed.
 */

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Use the full Laravel vendor from Files/core/
require __DIR__ . '/../Files/core/vendor/autoload.php';

// Boot Laravel from the actual app directory
(require_once __DIR__ . '/../Files/core/bootstrap/app.php')
    ->handleRequest(Request::capture());
