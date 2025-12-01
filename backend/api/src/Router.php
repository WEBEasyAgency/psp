<?php

namespace PSP;

/**
 * Простой роутер для обработки HTTP запросов
 */
class Router {
    private $routes = [];

    /**
     * Регистрация GET маршрута
     */
    public function get($pattern, $callback) {
        $this->routes['GET'][$pattern] = $callback;
    }

    /**
     * Регистрация POST маршрута
     */
    public function post($pattern, $callback) {
        $this->routes['POST'][$pattern] = $callback;
    }

    /**
     * Запуск роутера
     */
    public function run() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Убираем префикс /backend/api или /api если они есть
        $path = preg_replace('#^/backend/api#', '', $path);
        $path = preg_replace('#^/api#', '', $path);
        // Убираем завершающий слэш
        $path = rtrim($path, '/');

        if (!isset($this->routes[$method])) {
            Response::error('Method not allowed', 405);
            return;
        }

        foreach ($this->routes[$method] as $pattern => $callback) {
            // Преобразуем паттерн в регулярное выражение
            $regex = '#^' . $pattern . '$#';

            if (preg_match($regex, $path, $matches)) {
                // Убираем первый элемент (полное совпадение)
                array_shift($matches);

                // Вызываем callback с параметрами
                call_user_func_array($callback, $matches);
                return;
            }
        }

        Response::error('Endpoint not found', 404);
    }
}
