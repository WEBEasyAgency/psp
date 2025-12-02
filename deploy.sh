#!/bin/bash

# Скрипт для деплоя Laravel + Vue приложения на удалённый сервер
# Запускается на удалённом сервере после git pull

set -e  # Остановка при ошибке

echo "🚀 Starting deployment..."

# Переход в директорию проекта
PROJECT_DIR="/home/abrobe14/psp.realeasystudio.site"
cd $PROJECT_DIR

echo "📥 Pulling latest changes from git..."
git pull origin main

echo "📦 Installing Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

echo "📦 Installing NPM dependencies..."
npm ci --production=false

echo "🏗️  Building frontend assets..."
npm run build

echo "🔧 Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment completed successfully!"
