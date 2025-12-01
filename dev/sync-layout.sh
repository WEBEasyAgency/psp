#!/bin/bash

# Скрипт для синхронизации JS из ../layout/ в public/layout/
# CSS и шрифты импортируются напрямую через @import в main.css

echo "Синхронизация JS из layout..."

# Создаём директорию если её нет
mkdir -p public/layout/js

# Копируем только JS файлы
echo "Копирование JS..."
cp ../layout/js/*.min.js public/layout/js/

echo "✅ Синхронизация завершена!"
echo "📝 CSS и шрифты берутся напрямую из ../layout/ через @import"
