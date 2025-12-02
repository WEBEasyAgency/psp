@extends('layouts.app')

@section('title', 'PSP - Онлайн калькулятор рекламных конструкций')

@section('content')
<div class="main-page">
    <div class="container">
        <section class="py-12">
            <h1 class="text-4xl font-bold mb-8 text-center">Добро пожаловать в PSP Online</h1>
            <p class="text-center text-xl mb-8">Платформа онлайн расчета рекламных конструкций</p>

            <div id="home-app">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-xl font-bold mb-4">Объемные буквы</h3>
                        <p class="mb-4">Рассчитайте стоимость объемных букв онлайн</p>
                        <a href="/product/146" class="btn btn-primary">Перейти к калькулятору</a>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-xl font-bold mb-4">Несветовые вывески</h3>
                        <p class="mb-4">Онлайн расчет вывесок из различных материалов</p>
                        <a href="/product/156" class="btn btn-primary">Перейти к калькулятору</a>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-xl font-bold mb-4">Акриловые вывески</h3>
                        <p class="mb-4">Расчет акриловых вывесок с подсветкой</p>
                        <a href="/product/157" class="btn btn-primary">Перейти к калькулятору</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@push('scripts')
@vite('resources/js/pages/home.js')
@endpush

{{-- Deployment test: 2025-12-02 --}}
