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

// Страница оформления заказа (добавим позже)
Route::get('/order', function () {
    return view('order');
});

// Страница благодарности (добавим позже)
Route::get('/thanx', function () {
    return view('thanx');
});
