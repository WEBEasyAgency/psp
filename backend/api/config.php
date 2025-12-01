<?php
/**
 * Конфигурация API
 */

return [
    /**
     * Режим работы API
     * 'mock' - использовать моковые данные (по умолчанию)
     * 'real' - использовать реальное API
     */
    'mode' => getenv('API_MODE') ?: 'real',

    /**
     * Базовый URL реального API
     * По умолчанию используется URL из спецификации RestApi New.yaml
     * ВАЖНО: Базовый путь /api/calc/, а не /api/
     */
    'real_api_url' => getenv('REAL_API_URL') ?: 'http://5.188.117.42:9000/api/calc',

    /**
     * Учетные данные для реального API
     * ВАЖНО: Эти данные НЕ должны передаваться на клиент!
     */
    'api_credentials' => [
        'db_id' => 1,
        'user' => 'Online@1-erp.ru',
        'pass' => 'aS6Y-qs1cGXvHerl'
    ],

    /**
     * Настройки логирования
     */
    'logging' => [
        'enabled' => true,
        'log_file' => __DIR__ . '/logs/api.log'
    ]
];
