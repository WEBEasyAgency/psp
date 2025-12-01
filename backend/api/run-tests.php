<?php
/**
 * Веб-интерфейс для запуска PHPUnit тестов
 * Доступ: https://psp.realeasystudio.site/backend/api/run-tests.php
 */

// Безопасность: можно добавить проверку пароля
// $password = $_GET['key'] ?? '';
// if ($password !== 'your_secret_key') die('Access denied');

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>PSP API Tests</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 20px;
            margin: 0;
        }
        h1 {
            color: #4ec9b0;
            border-bottom: 2px solid #4ec9b0;
            padding-bottom: 10px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #252526;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.3);
        }
        .test-output {
            background: #1e1e1e;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            white-space: pre-wrap;
            border: 1px solid #3e3e42;
            margin-top: 20px;
        }
        .success { color: #4ec9b0; }
        .error { color: #f48771; }
        .warning { color: #dcdcaa; }
        .info { color: #569cd6; }
        .btn {
            background: #0e639c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 14px;
            margin: 5px;
        }
        .btn:hover {
            background: #1177bb;
        }
        .stats {
            display: flex;
            gap: 20px;
            margin: 20px 0;
        }
        .stat-box {
            background: #2d2d30;
            padding: 15px;
            border-radius: 4px;
            flex: 1;
            text-align: center;
        }
        .stat-box .number {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stat-box .label {
            color: #858585;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 PSP Calc API - Test Suite</h1>

        <div class="info">
            <strong>Сервер:</strong> <?php echo $_SERVER['SERVER_NAME']; ?><br>
            <strong>PHP версия:</strong> <?php echo PHP_VERSION; ?><br>
            <strong>Время:</strong> <?php echo date('Y-m-d H:i:s'); ?>
        </div>

        <div style="margin-top: 20px;">
            <button class="btn" onclick="runTests('all')">▶ Запустить все тесты</button>
            <button class="btn" onclick="runTests('mock')">▶ MockData тесты</button>
            <button class="btn" onclick="runTests('api')">▶ ApiClient тесты</button>
            <button class="btn" onclick="runTests('router')">▶ Router тесты</button>
        </div>

<?php
if (isset($_GET['run'])) {
    $testType = $_GET['run'];

    // Определяем базовую директорию проекта (backend/)
    $baseDir = realpath(__DIR__ . '/..');

    // Возможные пути к PHPUnit
    $possiblePaths = [
        $baseDir . '/vendor/bin/phpunit',
        __DIR__ . '/../vendor/bin/phpunit',
        '/usr/local/bin/phpunit',
        '/usr/bin/phpunit',
        'phpunit' // Глобальная установка
    ];

    // Ищем PHPUnit
    $phpunitPath = null;
    foreach ($possiblePaths as $path) {
        if (file_exists($path) || $path === 'phpunit') {
            $phpunitPath = $path;
            break;
        }
    }

    // Путь к тестам
    $testsPath = $baseDir . '/tests/';

    // Определяем какие тесты запускать
    switch ($testType) {
        case 'mock':
            $testFile = $testsPath . 'MockDataTest.php';
            break;
        case 'api':
            $testFile = $testsPath . 'ApiClientTest.php';
            break;
        case 'router':
            $testFile = $testsPath . 'RouterTest.php';
            break;
        default:
            $testFile = ''; // Все тесты
    }

    // Проверяем наличие PHPUnit
    if (!$phpunitPath) {
        echo '<div class="stats">';
        echo '<div class="stat-box status-error"><div class="number error">✗</div><div class="label">PHPUnit не найден</div></div>';
        echo '</div>';

        echo '<div class="test-output">';
        echo '<span class="error">Ошибка: PHPUnit не найден</span>' . "\n\n";
        echo 'Проверенные пути:' . "\n";
        foreach ($possiblePaths as $path) {
            echo '  - ' . $path . ' [' . (file_exists($path) ? 'найден' : 'не найден') . ']' . "\n";
        }
        echo "\nБазовая директория: " . $baseDir . "\n";
        echo 'Текущая директория: ' . __DIR__ . "\n";
        echo "\nРешение: Выполните 'composer install' в директории backend/\n";
        echo '</div>';
    } else {
        // Формируем команду
        // Запускаем через php для избежания проблем с правами
        if ($testFile) {
            $command = "cd " . escapeshellarg($baseDir) . " && php " . escapeshellarg($phpunitPath) . " " . escapeshellarg($testFile) . " --testdox 2>&1";
        } else {
            $command = "cd " . escapeshellarg($baseDir) . " && php " . escapeshellarg($phpunitPath) . " --testdox 2>&1";
        }

        echo '<div class="stats">';
        echo '<div class="stat-box"><div class="number warning">⏳</div><div class="label">Выполняется...</div></div>';
        echo '</div>';

        echo '<div class="test-output">';
        echo '<span class="info">$ ' . htmlspecialchars($command) . '</span>' . "\n\n";

        // Выполняем команду
        $output = [];
        $returnCode = 0;
        exec($command, $output, $returnCode);

        // Выводим результат
        $outputText = implode("\n", $output);

        // Парсим статистику
        preg_match('/Tests: (\d+)/', $outputText, $testsMatch);
        preg_match('/Assertions: (\d+)/', $outputText, $assertionsMatch);
        preg_match('/Skipped: (\d+)/', $outputText, $skippedMatch);

        $totalTests = $testsMatch[1] ?? '?';
        $totalAssertions = $assertionsMatch[1] ?? '?';
        $skippedTests = $skippedMatch[1] ?? '0';

        // Подсветка синтаксиса
        $outputText = str_replace('✔', '<span class="success">✔</span>', $outputText);
        $outputText = str_replace('✗', '<span class="error">✗</span>', $outputText);
        $outputText = str_replace('↩', '<span class="warning">↩</span>', $outputText);
        $outputText = preg_replace('/\[(\d+m)/', '', $outputText); // Убираем ANSI коды

        echo htmlspecialchars($outputText);
        echo '</div>';

        // Показываем статистику
        echo '<script>';
        echo 'document.querySelector(".stats").innerHTML = `';
        echo '<div class="stat-box ' . ($returnCode === 0 ? 'success' : 'error') . '">';
        echo '<div class="number">' . $totalTests . '</div>';
        echo '<div class="label">Тестов выполнено</div>';
        echo '</div>';
        echo '<div class="stat-box success">';
        echo '<div class="number">' . $totalAssertions . '</div>';
        echo '<div class="label">Утверждений проверено</div>';
        echo '</div>';
        if ($skippedTests > 0) {
            echo '<div class="stat-box warning">';
            echo '<div class="number">' . $skippedTests . '</div>';
            echo '<div class="label">Пропущено</div>';
            echo '</div>';
        }
        echo '<div class="stat-box ' . ($returnCode === 0 ? 'success' : 'error') . '">';
        echo '<div class="number">' . ($returnCode === 0 ? '✓' : '✗') . '</div>';
        echo '<div class="label">' . ($returnCode === 0 ? 'Успешно' : 'Есть ошибки') . '</div>';
        echo '</div>';
        echo '`;';
        echo '</script>';
    }
}
?>

    </div>

    <script>
        function runTests(type) {
            window.location.href = '?run=' + type;
        }
    </script>
</body>
</html>
