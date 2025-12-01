<?php
/**
 * PSP Calc API - Main Router
 * Mock API endpoints для работы с калькуляторами
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Обработка preflight запросов
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// IMPORTANT: Load Composer autoloader first for Guzzle and other dependencies
require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/src/Router.php';
require_once __DIR__ . '/src/Response.php';
require_once __DIR__ . '/src/MockData.php';
require_once __DIR__ . '/src/ApiClient.php';

use PSP\Router;
use PSP\Response;
use PSP\MockData;

// Загрузка конфигурации
$config = require_once __DIR__ . '/config.php';

// Включение реального API, если настроено в конфигурации
if ($config['mode'] === 'real') {
    MockData::enableRealApi($config['real_api_url']);
}

$router = new Router();

// GET /calcs - получение списка калькуляторов
$router->get('/calcs', function() {
    $data = MockData::getCalcs();
    Response::json($data);
});

// POST /calc/:id/params - получение параметров калькулятора
$router->post('/calc/(\d+)/params', function($id) use ($config) {
    // Используем credentials из конфигурации (НЕ от клиента!)
    $credentials = $config['api_credentials'];

    $data = MockData::getParams($id, $credentials);
    Response::json($data);
});

// POST /calc/:id/run - выполнение расчёта
$router->post('/calc/(\d+)/run', function($id) use ($config) {
    try {
        $rawInput = file_get_contents('php://input');
        $input = json_decode($rawInput, true);

        // Отладка
        if ($input === null) {
            Response::error('Invalid JSON: ' . json_last_error_msg() . '. Raw: ' . substr($rawInput, 0, 200), 400);
        }

        // Валидация обязательных полей (только params от клиента)
        if (!isset($input['params'])) {
            Response::error('Missing required field: params. Got: ' . json_encode(array_keys($input ?? [])), 400);
        }

        // Объединяем credentials из конфига с параметрами от клиента
        $requestData = array_merge($config['api_credentials'], $input);

        $data = MockData::runCalculation($id, $requestData);
        Response::json($data);
    } catch (\Exception $e) {
        Response::error('Calculation error: ' . $e->getMessage(), 500);
    }
});

// POST /calc/addCalc - добавление расчёта в калькуляцию (NEW API)
$router->post('/calc/addCalc', function() use ($config) {
    $input = json_decode(file_get_contents('php://input'), true);

    // Валидация обязательных полей
    $required = ['calc_position_id', 'price_good'];
    foreach ($required as $field) {
        if (!isset($input[$field])) {
            Response::error("Missing required field: $field", 400);
        }
    }

    // Объединяем credentials из конфига с параметрами от клиента
    $requestData = array_merge($config['api_credentials'], $input);

    $data = MockData::addToCalculation($requestData);
    Response::json($data);
});

// POST /calc/delCalc - удаление расчёта (NEW API)
$router->post('/calc/delCalc', function() use ($config) {
    $input = json_decode(file_get_contents('php://input'), true);

    // Валидация обязательных полей
    $required = ['calc_position_id'];
    foreach ($required as $field) {
        if (!isset($input[$field])) {
            Response::error("Missing required field: $field", 400);
        }
    }

    // Объединяем credentials из конфига с параметрами от клиента
    $requestData = array_merge($config['api_credentials'], $input);

    $data = MockData::deleteCalculation($requestData);
    Response::json($data);
});

// POST /calc/saveCalc - сохранение калькуляции (NEW API)
$router->post('/calc/saveCalc', function() use ($config) {
    $input = json_decode(file_get_contents('php://input'), true);

    // Валидация обязательных полей (ВАЖНО: calc_id и client_id, а не calc__id и clientId!)
    $required = ['calc_id', 'client_id'];
    foreach ($required as $field) {
        if (!isset($input[$field])) {
            Response::error("Missing required field: $field", 400);
        }
    }

    // Объединяем credentials из конфига с параметрами от клиента
    $requestData = array_merge($config['api_credentials'], $input);

    $data = MockData::saveCalculation($requestData);
    Response::json($data);
});

// POST /calc/addLink - добавление ссылки на файл (NEW API)
$router->post('/calc/addLink', function() use ($config) {
    $input = json_decode(file_get_contents('php://input'), true);

    // Валидация обязательных полей
    $required = ['calc_id', 'link'];
    foreach ($required as $field) {
        if (!isset($input[$field])) {
            Response::error("Missing required field: $field", 400);
        }
    }

    // Объединяем credentials из конфига с параметрами от клиента
    $requestData = array_merge($config['api_credentials'], $input);

    $data = MockData::addLink($requestData);
    Response::json($data);
});

// POST /calc/addContact - добавление контакта клиента (NEW API)
$router->post('/calc/addContact', function() use ($config) {
    $input = json_decode(file_get_contents('php://input'), true);

    // Валидация обязательных полей
    $required = ['fio'];
    foreach ($required as $field) {
        if (!isset($input[$field])) {
            Response::error("Missing required field: $field", 400);
        }
    }

    // Объединяем credentials из конфига с параметрами от клиента
    $requestData = array_merge($config['api_credentials'], $input);

    $data = MockData::addContact($requestData);
    Response::json($data);
});

// Запуск роутера
$router->run();
