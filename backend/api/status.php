<?php
/**
 * Страница статуса API
 * Доступ: https://psp.realeasystudio.site/backend/api/status.php
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/src/MockData.php';
require_once __DIR__ . '/src/ApiClient.php';
require_once __DIR__ . '/config.php';

use PSP\MockData;
use PSP\ApiClient;

header('Content-Type: text/html; charset=utf-8');

$config = require __DIR__ . '/config.php';
$mode = $config['mode'];
$apiUrl = $config['real_api_url'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>PSP API Status</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
            padding: 20px;
            margin: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        h1 {
            color: #667eea;
            margin-top: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .status-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #667eea;
        }
        .status-ok {
            border-left-color: #28a745;
        }
        .status-error {
            border-left-color: #dc3545;
        }
        .status-warning {
            border-left-color: #ffc107;
        }
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-success {
            background: #28a745;
            color: white;
        }
        .badge-danger {
            background: #dc3545;
            color: white;
        }
        .badge-warning {
            background: #ffc107;
            color: #333;
        }
        .badge-info {
            background: #17a2b8;
            color: white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #dee2e6;
        }
        td:first-child {
            font-weight: bold;
            width: 180px;
            color: #666;
        }
        .test-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .test-link:hover {
            background: #764ba2;
        }
        .endpoints {
            list-style: none;
            padding: 0;
        }
        .endpoints li {
            padding: 8px;
            background: white;
            margin: 5px 0;
            border-radius: 4px;
            font-family: monospace;
        }
        .endpoints li::before {
            content: "→ ";
            color: #667eea;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>
            <span style="font-size: 40px;"></span>
            PSP Calc API Status
        </h1>

        <div class="status-card status-<?php echo $mode === 'real' ? 'ok' : 'warning'; ?>">
            <h3 style="margin-top: 0;">
                Текущий режим:
                <span class="badge badge-<?php echo $mode === 'real' ? 'info' : 'warning'; ?>">
                    <?php echo strtoupper($mode); ?> MODE
                </span>
            </h3>
            <table>
                <tr>
                    <td>Сервер:</td>
                    <td><?php echo $_SERVER['SERVER_NAME']; ?></td>
                </tr>
                <tr>
                    <td>PHP версия:</td>
                    <td><?php echo PHP_VERSION; ?></td>
                </tr>
                <tr>
                    <td>Время сервера:</td>
                    <td><?php echo date('Y-m-d H:i:s'); ?></td>
                </tr>
                <tr>
                    <td>Реальное API:</td>
                    <td><code><?php echo htmlspecialchars($apiUrl); ?></code></td>
                </tr>
                <tr>
                    <td>Mock данные:</td>
                    <td><?php echo MockData::isUsingRealApi() ? '❌ Отключено' : '✅ Активно'; ?></td>
                </tr>
            </table>
        </div>

        <div class="status-card">
            <h3 style="margin-top: 0;">Проверка соединения</h3>
            <?php
            try {
                $testClient = new ApiClient($apiUrl);
                $result = $testClient->getCalcs();

                echo '<p class="badge badge-success">✓ API доступен</p>';
                echo '<p>Получено калькуляторов: <strong>' . count($result['iCalcs']) . '</strong></p>';
            } catch (\Exception $e) {
                echo '<p class="badge badge-danger">✗ Ошибка подключения</p>';
                echo '<p style="color: #dc3545;">Ошибка: ' . htmlspecialchars($e->getMessage()) . '</p>';
                echo '<p style="color: #666; font-size: 14px;">Автоматически используются mock данные</p>';
            }
            ?>
        </div>

        <div class="status-card">
            <h3 style="margin-top: 0;">Доступные эндпоинты</h3>
            <ul class="endpoints">
                <li>GET /calcs</li>
                <li>POST /:id/params</li>
                <li>POST /:id/run</li>
                <li>POST /:id/add</li>
                <li>POST /:id/save</li>
            </ul>
        </div>

        <div class="status-card">
            <h3 style="margin-top: 0;">Быстрый тест</h3>
            <?php
            // Тестируем endpoint /calcs
            $testUrl = 'https://' . $_SERVER['SERVER_NAME'] . '/backend/api/calcs';
            echo '<p>Тестирую: <code>' . htmlspecialchars($testUrl) . '</code></p>';

            try {
                $ch = curl_init($testUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode === 200) {
                    $data = json_decode($response, true);
                    echo '<p class="badge badge-success">✓ HTTP 200 OK</p>';
                    echo '<p>Ответ содержит: <strong>' . count($data['iCalcs']) . ' калькуляторов</strong></p>';
                } else {
                    echo '<p class="badge badge-danger">✗ HTTP ' . $httpCode . '</p>';
                }
            } catch (\Exception $e) {
                echo '<p class="badge badge-danger">✗ Ошибка</p>';
            }
            ?>
        </div>

        <a href="run-tests.php" class="test-link">Запустить тесты PHPUnit</a>
        <a href="<?php echo $testUrl; ?>" class="test-link" target="_blank">Посмотреть JSON ответ</a>
    </div>
</body>
</html>
