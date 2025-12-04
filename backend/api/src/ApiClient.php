<?php

namespace PSP;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Клиент для работы с удалённым PSP API
 */
class ApiClient {
    private $client;
    private $baseUrl;

    /**
     * @param string $baseUrl Базовый URL API (по умолчанию из спецификации)
     */
    public function __construct($baseUrl = 'http://5.188.117.42:9000/api/calc') {
        // Сохраняем без слэша для getBaseUrl()
        $this->baseUrl = rtrim($baseUrl, '/');
        // Но для Guzzle добавляем слэш в конце, чтобы относительные пути работали корректно
        $this->client = new Client([
            'base_uri' => $this->baseUrl . '/',
            'timeout' => 10.0,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);
    }

    /**
     * Обработка ошибок Guzzle
     * Пытается извлечь сообщение об ошибке из тела ответа
     */
    private function handleGuzzleError(GuzzleException $e, $contextMessage) {
        $message = $e->getMessage(); // Fallback message

        // Check if we have a response
        if (method_exists($e, 'getResponse') && $e->getResponse()) {
            try {
                $response = $e->getResponse();
                $response->getBody()->rewind();
                $body = $response->getBody()->getContents();
                
                if (!empty($body)) {
                    $json = json_decode($body, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        // Try typical error fields
                        if (!empty($json['error'])) {
                            $message = is_string($json['error']) ? $json['error'] : json_encode($json['error'], JSON_UNESCAPED_UNICODE);
                        } elseif (!empty($json['message'])) {
                            $message = is_string($json['message']) ? $json['message'] : json_encode($json['message'], JSON_UNESCAPED_UNICODE);
                        } elseif (!empty($json['detail'])) {
                            $message = is_string($json['detail']) ? $json['detail'] : json_encode($json['detail'], JSON_UNESCAPED_UNICODE);
                        }
                    } else {
                        // Not JSON, maybe plain text error?
                        // Use body if it's reasonably short (e.g. < 1000 chars)
                        if (strlen($body) < 1000) {
                            $message = strip_tags($body);
                        }
                    }
                }
            } catch (\Exception $ex) {
                // Ignore errors during error handling, continue with default message
            }
        }

        throw new \Exception($message);
    }

    /**
     * Получение списка калькуляторов
     * GET /calcs
     *
     * @return array
     * @throws \Exception
     */
    public function getCalcs() {
        try {
            $response = $this->client->get('calcs');
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e, 'Failed to fetch calculators');
        }
    }

    /**
     * Получение параметров калькулятора
     * POST /:id/params
     *
     * @param int $calcId ID калькулятора
     * @param array $credentials ['db_id' => int, 'user' => string, 'pass' => string]
     * @return array
     * @throws \Exception
     */
    public function getParams($calcId, $credentials) {
        try {
            $response = $this->client->post("{$calcId}/params", [
                'json' => $credentials
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e, 'Failed to fetch calculator parameters');
        }
    }

    /**
     * Выполнение расчёта
     * POST /:id/run
     *
     * @param int $calcId ID калькулятора
     * @param array $data Данные для расчёта (db_id, user, pass, params, mat_select_params)
     * @return array
     * @throws \Exception
     */
    public function runCalculation($calcId, $data) {
        try {
            $response = $this->client->post("{$calcId}/run", [
                'json' => $data
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e, 'Failed to run calculation');
        }
    }

    /**
     * Добавление расчёта в калькуляцию
     * POST /addCalc (NEW API)
     *
     * @param array $data Данные (db_id, user, pass, calc_id, calc_position_id, price_good)
     * @return array
     * @throws \Exception
     */
    public function addToCalculation($data) {
        try {
            $response = $this->client->post("addCalc", [
                'json' => $data
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e, 'Failed to add to calculation');
        }
    }

    /**
     * Сохранение калькуляции
     * POST /saveCalc (NEW API)
     *
     * @param array $data Данные (db_id, user, pass, calc__id, clientId)
     * @return array
     * @throws \Exception
     */
    public function saveCalculation($data) {
        try {
            $response = $this->client->post("saveCalc", [
                'json' => $data
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e, 'Failed to save calculation');
        }
    }

    /**
     * Удаление расчёта
     * POST /delCalc (NEW API)
     *
     * @param array $data Данные (db_id, user, pass, calc_position_id)
     * @return array
     * @throws \Exception
     */
    public function deleteCalculation($data) {
        try {
            $response = $this->client->post("delCalc", [
                'json' => $data
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e, 'Failed to delete calculation');
        }
    }

    /**
     * Добавление ссылки на файл в калькуляцию
     * POST /addLink (NEW API)
     *
     * @param array $data Данные (db_id, user, pass, calc_id, link)
     * @return array
     * @throws \Exception
     */
    public function addLink($data) {
        try {
            $response = $this->client->post("addLink", [
                'json' => $data
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e, 'Failed to add link');
        }
    }

    /**
     * Добавление данных о клиенте
     * POST /addContact (NEW API)
     *
     * @param array $data Данные (db_id, user, pass, fio, phone, email)
     * @return array
     * @throws \Exception
     */
    public function addContact($data) {
        try {
            $response = $this->client->post("addContact", [
                'json' => $data
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            $this->handleGuzzleError($e, 'Failed to add contact');
        }
    }

    /**
     * Получить базовый URL API
     *
     * @return string
     */
    public function getBaseUrl() {
        return $this->baseUrl;
    }
}