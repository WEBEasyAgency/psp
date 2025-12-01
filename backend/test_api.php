<?php
/**
 * Тестовый скрипт для проверки подключения к API
 */

require_once __DIR__ . '/vendor/autoload.php';

use PSP\ApiClient;

echo "=== Тестирование подключения к реальному API ===\n\n";

try {
    $client = new ApiClient();
    echo "✓ ApiClient создан\n";
    echo "  Base URL: " . $client->getBaseUrl() . "\n\n";

    echo "Попытка получить список калькуляторов...\n";
    $result = $client->getCalcs();

    echo "✓ Успешно! Получено калькуляторов: " . count($result['iCalcs']) . "\n\n";
    echo "Первые 3 калькулятора:\n";

    foreach (array_slice($result['iCalcs'], 0, 3) as $calc) {
        echo "  - ID: {$calc['id']}, Name: {$calc['name']}\n";
    }

} catch (\Exception $e) {
    echo "✗ Ошибка: " . $e->getMessage() . "\n";
    echo "\nТип ошибки: " . get_class($e) . "\n";

    if ($e->getPrevious()) {
        echo "Предыдущая ошибка: " . $e->getPrevious()->getMessage() . "\n";
    }
}
