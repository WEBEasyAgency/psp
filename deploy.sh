#!/bin/bash

# Скрипт для деплоя Laravel + Vue приложения на BeGet хостинг
# Запускается на удалённом сервере после git pull
#
# ВАЖНО: На BeGet хостинге:
# - PHP CLI по умолчанию = 5.6.40, нужно использовать /usr/local/bin/php8.4
# - Composer установлен локально в ~/bin/composer
# - Node.js может отсутствовать, поэтому фронтенд собирается локально
# - Document root = public_html (автоматически, мы уже там)

set -e  # Остановка при ошибке

echo "🚀 Starting deployment on BeGet hosting..."

# Пути к правильным версиям инструментов
PHP="/usr/local/bin/php8.4"
COMPOSER="$HOME/bin/composer"

# Проверка наличия необходимых инструментов
if [ ! -f "$PHP" ]; then
    echo "❌ Error: PHP 8.4 not found at $PHP"
    exit 1
fi

if [ ! -f "$COMPOSER" ]; then
    echo "❌ Error: Composer not found at $COMPOSER"
    exit 1
fi

echo "✅ PHP version: $($PHP -v | head -1)"
echo "✅ Composer version: $($PHP $COMPOSER --version --no-ansi | head -1)"

echo ""
echo "📥 Pulling latest changes from git..."
git pull origin main --ff-only

echo ""
echo "📦 Installing/updating Composer dependencies..."
$PHP $COMPOSER install --no-interaction --prefer-dist --optimize-autoloader --no-dev

echo ""
echo "🔧 Clearing Laravel caches..."
$PHP artisan config:clear
$PHP artisan route:clear
$PHP artisan view:clear

echo ""
echo "📊 Verifying storage permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo ""
echo "✅ Deployment completed successfully!"
echo ""
echo "📝 Note: Frontend assets (public/build/) should be built locally and deployed via git"
echo "   Run locally: npm run build && git add public/build && git commit && git push"
