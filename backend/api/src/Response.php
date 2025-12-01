<?php

namespace PSP;

/**
 * Класс для формирования HTTP ответов
 */
class Response {

    /**
     * Отправка JSON ответа
     */
    public static function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Отправка ошибки
     */
    public static function error($message, $statusCode = 400) {
        http_response_code($statusCode);
        echo json_encode([
            'error' => $message,
            'status' => $statusCode
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}
