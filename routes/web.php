<?php

use Illuminate\Support\Facades\Route;

// Главная страница
Route::get('/', function () {
    return view('welcome');
});

// Страницы продуктов/калькуляторов (добавим позже)
Route::get('/product/{id}', function ($id) {
    return view('product', ['calculatorId' => $id]);
})->where('id', '[0-9]+');

// Страница оформления заказа
Route::get('/order', function (Illuminate\Http\Request $request) {
    $calc_position_id = $request->get('calc_position_id', 0);
    $price_good = $request->get('price', 0);
    $description = $request->get('desc', 'Заказ');

    // Проверка обязательных параметров
    if (!$calc_position_id || !$price_good) {
        abort(400, 'Ошибка: отсутствуют данные заказа.');
    }

    return view('order', [
        'calc_position_id' => (int)$calc_position_id,
        'price_good' => (int)$price_good,
        'description' => htmlspecialchars($description)
    ]);
});

// Страница благодарности
Route::get('/thanx', function (Illuminate\Http\Request $request) {
    $calc_id = $request->get('calc_id', 0);
    $client_id = $request->get('client_id', 0);

    // Если параметры отсутствуют, можно показать базовое сообщение
    return view('thanx', [
        'calc_id' => (int)$calc_id,
        'client_id' => (int)$client_id
    ]);
});
