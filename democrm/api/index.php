<?php

/**
 * Vercel Serverless Entry Point — CrossFlow CRM
 *
 * This file is the PHP function that Vercel invokes for every request
 * to democrm.crosstechsolutions.in. It simply delegates to the
 * Laravel entry point in Files/index.php.
 *
 * Vercel deploys with vercel-php@0.7.4 (PHP 8.3).
 */

require __DIR__ . '/../Files/index.php';
