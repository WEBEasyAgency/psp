<?php
/**
 * Comprehensive diagnostic script to identify HTTP 500 error
 * Access: https://psp.realeasystudio.site/backend/api/debug.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');

$results = [
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => PHP_VERSION,
    'errors' => []
];

echo "<!DOCTYPE html><html><head><title>API Debug</title><style>
body { font-family: monospace; background: #1e1e1e; color: #d4d4d4; padding: 20px; }
h2 { color: #4ec9b0; border-bottom: 2px solid #4ec9b0; padding-bottom: 5px; }
.success { color: #4ec9b0; }
.error { color: #f48771; }
.warning { color: #dcdcaa; }
pre { background: #252526; padding: 10px; border-radius: 4px; overflow-x: auto; }
table { border-collapse: collapse; width: 100%; }
td { padding: 5px; border: 1px solid #3e3e42; }
td:first-child { font-weight: bold; width: 200px; }
</style></head><body>";

echo "<h1>🔍 PSP API Diagnostic Report</h1>";

// Test 1: File system check
echo "<h2>1. File System Check</h2><table>";
$files = [
    'Autoloader' => __DIR__ . '/../vendor/autoload.php',
    'Config' => __DIR__ . '/config.php',
    'Router' => __DIR__ . '/src/Router.php',
    'MockData' => __DIR__ . '/src/MockData.php',
    'ApiClient' => __DIR__ . '/src/ApiClient.php',
    'Response' => __DIR__ . '/src/Response.php',
    'index.php' => __DIR__ . '/index.php'
];

foreach ($files as $name => $path) {
    $exists = file_exists($path);
    $readable = $exists ? is_readable($path) : false;
    $status = $exists && $readable ? '✓' : '✗';
    $class = $exists && $readable ? 'success' : 'error';
    echo "<tr><td>$name</td><td class='$class'>$status " . ($exists ? 'EXISTS' : 'MISSING') .
         ($exists && !$readable ? ' (NOT READABLE)' : '') . "</td></tr>";
    if (!$exists || !$readable) {
        $results['errors'][] = "$name file issue: $path";
    }
}
echo "</table>";

// Test 2: Autoloader
echo "<h2>2. Autoloader Test</h2>";
try {
    require_once __DIR__ . '/../vendor/autoload.php';
    echo "<p class='success'>✓ Autoloader loaded successfully</p>";
    $results['autoloader'] = 'OK';
} catch (Exception $e) {
    echo "<p class='error'>✗ Autoloader failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    $results['errors'][] = "Autoloader: " . $e->getMessage();
}

// Test 3: Config loading
echo "<h2>3. Config Loading</h2>";
try {
    $config = require __DIR__ . '/config.php';
    echo "<p class='success'>✓ Config loaded</p>";
    echo "<pre>" . htmlspecialchars(print_r($config, true)) . "</pre>";
    $results['config'] = $config;
} catch (Exception $e) {
    echo "<p class='error'>✗ Config failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    $results['errors'][] = "Config: " . $e->getMessage();
}

// Test 4: Class loading
echo "<h2>4. Class Loading</h2><table>";
$classes = ['PSP\\Router', 'PSP\\Response', 'PSP\\MockData', 'PSP\\ApiClient'];
foreach ($classes as $class) {
    try {
        if (class_exists($class)) {
            echo "<tr><td>$class</td><td class='success'>✓ Loaded</td></tr>";
        } else {
            echo "<tr><td>$class</td><td class='error'>✗ Not found</td></tr>";
            $results['errors'][] = "Class not found: $class";
        }
    } catch (Exception $e) {
        echo "<tr><td>$class</td><td class='error'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
        $results['errors'][] = "$class: " . $e->getMessage();
    }
}
echo "</table>";

// Test 5: MockData direct test
echo "<h2>5. MockData Direct Test</h2>";
try {
    if (isset($config) && $config['mode'] === 'real') {
        \PSP\MockData::enableRealApi($config['real_api_url']);
        echo "<p class='warning'>⚠ Real API mode enabled: " . htmlspecialchars($config['real_api_url']) . "</p>";
    }

    $calcs = \PSP\MockData::getCalcs();
    echo "<p class='success'>✓ MockData::getCalcs() executed</p>";
    echo "<pre>" . htmlspecialchars(json_encode($calcs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . "</pre>";
    $results['mockdata_test'] = 'OK';
} catch (Exception $e) {
    echo "<p class='error'>✗ MockData test failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>Stack trace:\n" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    $results['errors'][] = "MockData: " . $e->getMessage();
}

// Test 6: Router test
echo "<h2>6. Router Test</h2>";
try {
    $router = new \PSP\Router();

    // Simulate GET /calcs
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $testPath = '/calcs';

    echo "<p>Testing route: GET $testPath</p>";

    $router->get('/calcs', function() {
        $data = \PSP\MockData::getCalcs();
        return ['test' => 'success', 'data' => $data];
    });

    // Try to match
    $matched = false;
    ob_start();
    try {
        $router->dispatch($testPath);
        $matched = true;
    } catch (Exception $e) {
        echo "<p class='error'>✗ Dispatch failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    $output = ob_get_clean();

    if ($matched) {
        echo "<p class='success'>✓ Router dispatched successfully</p>";
        echo "<pre>" . htmlspecialchars($output) . "</pre>";
    }

    $results['router_test'] = $matched ? 'OK' : 'FAILED';
} catch (Exception $e) {
    echo "<p class='error'>✗ Router test failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>Stack trace:\n" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    $results['errors'][] = "Router: " . $e->getMessage();
}

// Test 7: Environment info
echo "<h2>7. Environment</h2><table>";
$env_vars = [
    'PHP Version' => PHP_VERSION,
    'Server Software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
    'Document Root' => $_SERVER['DOCUMENT_ROOT'] ?? 'N/A',
    'Script Filename' => __FILE__,
    'Working Directory' => getcwd(),
    'Include Path' => get_include_path(),
    'Memory Limit' => ini_get('memory_limit'),
    'Max Execution Time' => ini_get('max_execution_time'),
    'Error Reporting' => error_reporting(),
    'Display Errors' => ini_get('display_errors')
];

foreach ($env_vars as $key => $value) {
    echo "<tr><td>$key</td><td>" . htmlspecialchars($value) . "</td></tr>";
}
echo "</table>";

// Summary
echo "<h2>Summary</h2>";
if (empty($results['errors'])) {
    echo "<p class='success'>✓ All tests passed! No errors detected.</p>";
    echo "<p class='warning'>⚠ If index.php still returns 500, check:</p>";
    echo "<ul>";
    echo "<li>Apache error logs: <code>tail -f /var/log/apache2/error.log</code></li>";
    echo "<li>PHP error logs</li>";
    echo "<li>.htaccess rewrite rules</li>";
    echo "<li>Compare index.php with this debug.php setup</li>";
    echo "</ul>";
} else {
    echo "<p class='error'>✗ " . count($results['errors']) . " errors found:</p>";
    echo "<ul>";
    foreach ($results['errors'] as $error) {
        echo "<li class='error'>" . htmlspecialchars($error) . "</li>";
    }
    echo "</ul>";
}

echo "<h2>JSON Results</h2>";
echo "<pre>" . htmlspecialchars(json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . "</pre>";

echo "</body></html>";
