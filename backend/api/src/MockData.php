<?php

namespace PSP;

/**
 * Класс с моковыми данными для API
 * Поддерживает два режима работы:
 * - MOCK (по умолчанию): возвращает тестовые данные
 * - REAL: делает запросы к реальному API
 */
class MockData {
    /**
     * @var bool Режим работы (true = real API, false = mock data)
     */
    private static $useRealApi = false;

    /**
     * @var ApiClient|null Экземпляр API клиента
     */
    private static $apiClient = null;

    /**
     * Включить использование реального API
     *
     * @param string|null $baseUrl Базовый URL API (опционально)
     */
    public static function enableRealApi($baseUrl = null) {
        self::$useRealApi = true;
        self::$apiClient = new ApiClient($baseUrl);
    }

    /**
     * Отключить использование реального API (вернуться к моковым данным)
     */
    public static function disableRealApi() {
        self::$useRealApi = false;
        self::$apiClient = null;
    }

    /**
     * Проверить, используется ли реальное API
     *
     * @return bool
     */
    public static function isUsingRealApi() {
        return self::$useRealApi;
    }

    /**
     * Получить экземпляр API клиента
     *
     * @return ApiClient|null
     */
    public static function getApiClient() {
        return self::$apiClient;
    }

    /**
     * Получение списка калькуляторов
     */
    public static function getCalcs() {
        if (self::$useRealApi && self::$apiClient) {
            try {
                return self::$apiClient->getCalcs();
            } catch (\Exception $e) {
                // В случае ошибки логируем и возвращаем моковые данные
                error_log('API Error in getCalcs: ' . $e->getMessage());
                return self::getMockCalcs();
            }
        }
        return self::getMockCalcs();
    }

    /**
     * Моковые данные для списка калькуляторов
     */
    private static function getMockCalcs() {
        return [
            'iCalcs' => [
                [
                    'id' => 1,
                    'name' => 'Вывеска из ПВХ'
                ],
                [
                    'id' => 2,
                    'name' => 'Объёмные буквы'
                ],
                [
                    'id' => 3,
                    'name' => 'Таблички и указатели'
                ],
                [
                    'id' => 15,
                    'name' => 'Led-модули'
                ],
                [
                    'id' => 20,
                    'name' => 'Лайтбоксы'
                ]
            ]
        ];
    }

    /**
     * Получение параметров калькулятора
     */
    public static function getParams($calcId, $credentials = null) {
        if (self::$useRealApi && self::$apiClient && $credentials) {
            // Пробрасываем исключения напрямую - НЕ возвращаем mock данные
            return self::$apiClient->getParams($calcId, $credentials);
        }
        return self::getMockParams($calcId);
    }

    /**
     * Моковые данные для параметров калькулятора
     */
    private static function getMockParams($calcId) {
        return [
            'params' => [
                [
                    'id' => 53,
                    'variable' => 'l_fig_frez',
                    'caption' => 'Длина фигурной фрезеровки',
                    'type' => 1,
                    'default' => '0',
                    'value' => 0
                ],
                [
                    'id' => 54,
                    'variable' => 'width',
                    'caption' => 'Ширина (мм)',
                    'type' => 1,
                    'default' => '1000',
                    'value' => 1000
                ],
                [
                    'id' => 55,
                    'variable' => 'height',
                    'caption' => 'Высота (мм)',
                    'type' => 1,
                    'default' => '500',
                    'value' => 500
                ],
                [
                    'id' => 56,
                    'variable' => 'quantity',
                    'caption' => 'Количество',
                    'type' => 1,
                    'default' => '1',
                    'value' => 1
                ]
            ],
            'mat_select_params' => [
                [
                    'id' => 1,
                    'name' => 'Материал основы',
                    'in_prop_id' => 0,
                    'prop_id' => 55,
                    'mat_sel_link' => 55,
                    'mat_sel_link2' => 0,
                    'prop_type' => 3,
                    'variable' => 'mat_select',
                    'materials' => [
                        [
                            'id' => 101,
                            'name' => 'ПВХ пластик 2мм'
                        ],
                        [
                            'id' => 102,
                            'name' => 'ПВХ пластик 3мм'
                        ],
                        [
                            'id' => 103,
                            'name' => 'ПВХ пластик 5мм'
                        ],
                        [
                            'id' => 104,
                            'name' => 'Композит 3мм'
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * Выполнение расчёта
     */
    public static function runCalculation($calcId, $input) {
        if (self::$useRealApi && self::$apiClient) {
            // Пробрасываем исключения напрямую - НЕ возвращаем mock данные
            return self::$apiClient->runCalculation($calcId, $input);
        }
        return self::getMockCalculationResult($calcId, $input);
    }

    /**
     * Моковые данные для результата расчёта
     */
    private static function getMockCalculationResult($calcId, $input) {
        // Генерируем случайный ID расчёта
        $calcPositionId = isset($input['calc_position_id'])
            ? $input['calc_position_id']
            : rand(40000, 50000);

        // Вычисляем примерную стоимость на основе параметров
        $basePrice = 1000;
        $priceModifier = 1;

        if (isset($input['params']) && is_array($input['params'])) {
            foreach ($input['params'] as $param) {
                if (isset($param['variable']) && isset($param['value'])) {
                    switch ($param['variable']) {
                        case 'width':
                            $priceModifier *= ($param['value'] / 1000);
                            break;
                        case 'height':
                            $priceModifier *= ($param['value'] / 1000);
                            break;
                        case 'quantity':
                            $priceModifier *= $param['value'];
                            break;
                    }
                }
            }
        }

        $price = round($basePrice * $priceModifier);

        return [
            'calc_position_id' => $calcPositionId,
            'price_good' => $price
        ];
    }

    /**
     * Добавление расчёта в калькуляцию (NEW API)
     * POST /calc/addCalc
     */
    public static function addToCalculation($input) {
        if (self::$useRealApi && self::$apiClient) {
            // Пробрасываем исключения напрямую - НЕ возвращаем mock данные
            return self::$apiClient->addToCalculation($input);
        }
        return self::getMockAddToCalculationResult($input);
    }

    /**
     * Моковые данные для добавления в калькуляцию
     */
    private static function getMockAddToCalculationResult($input) {
        // Если calc_id не передан или равен 0, создаём новую калькуляцию
        $calcIdResult = isset($input['calc_id']) && $input['calc_id'] > 0
            ? $input['calc_id']
            : rand(41000000, 42000000);

        return [
            'calc_id' => $calcIdResult
        ];
    }

    /**
     * Сохранение калькуляции (NEW API)
     * POST /saveCalc
     */
    public static function saveCalculation($input) {
        if (self::$useRealApi && self::$apiClient) {
            try {
                return self::$apiClient->saveCalculation($input);
            } catch (\Exception $e) {
                error_log('API Error in saveCalculation: ' . $e->getMessage());
                return self::getMockSaveCalculationResult($input);
            }
        }
        return self::getMockSaveCalculationResult($input);
    }

    /**
     * Моковые данные для сохранения калькуляции
     */
    private static function getMockSaveCalculationResult($input) {
        return [
            'result' => 'OK'
        ];
    }

    /**
     * Удаление расчёта (NEW API)
     * POST /delCalc
     */
    public static function deleteCalculation($input) {
        if (self::$useRealApi && self::$apiClient) {
            try {
                return self::$apiClient->deleteCalculation($input);
            } catch (\Exception $e) {
                error_log('API Error in deleteCalculation: ' . $e->getMessage());
                return self::getMockDeleteCalculationResult($input);
            }
        }
        return self::getMockDeleteCalculationResult($input);
    }

    /**
     * Моковые данные для удаления расчёта
     */
    private static function getMockDeleteCalculationResult($input) {
        return [
            'status' => 'OK'
        ];
    }

    /**
     * Добавление ссылки на файл (NEW API)
     * POST /addLink
     */
    public static function addLink($input) {
        if (self::$useRealApi && self::$apiClient) {
            try {
                return self::$apiClient->addLink($input);
            } catch (\Exception $e) {
                error_log('API Error in addLink: ' . $e->getMessage());
                return self::getMockAddLinkResult($input);
            }
        }
        return self::getMockAddLinkResult($input);
    }

    /**
     * Моковые данные для добавления ссылки
     */
    private static function getMockAddLinkResult($input) {
        return [
            'result' => 'OK'
        ];
    }

    /**
     * Добавление контакта клиента (NEW API)
     * POST /calc/addContact
     */
    public static function addContact($input) {
        if (self::$useRealApi && self::$apiClient) {
            // Пробрасываем исключения напрямую - НЕ возвращаем mock данные
            return self::$apiClient->addContact($input);
        }
        return self::getMockAddContactResult($input);
    }

    /**
     * Моковые данные для добавления контакта
     */
    private static function getMockAddContactResult($input) {
        // ВАЖНО! Реальное API возвращает client_id, а не status
        return [
            'client_id' => rand(1000, 9999)
        ];
    }
}
