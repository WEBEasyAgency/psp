<?php

/**
 * Laravel Application Entry Point (BeGet Proxy)
 *
 * BeGet hosting требует, чтобы приложение было в public_html,
 * но Laravel ожидает, что document root указывает на public/.
 * Этот файл проксирует запросы к настоящему entry point.
 */

require __DIR__.'/public/index.php';
