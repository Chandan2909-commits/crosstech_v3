<?php

/**
 * Vercel Serverless Entry Point — CrossFlow CRM
 *
 * Must live at /api/index.php (Vercel's required location for PHP functions).
 * Delegates all requests to the Laravel entry point.
 */

require __DIR__ . '/../democrm/Files/index.php';
