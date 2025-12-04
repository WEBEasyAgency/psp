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
echo "📥 Updating code from git..."
git fetch origin main
git reset --hard origin/main

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
echo "🔗 Creating symlinks for static resources..."
# Remove old symlinks if they exist
rm -f img css js fonts build 2>/dev/null || true
# Create correct symlinks from root to public/
ln -sf public/img img 2>/dev/null || true
ln -sf public/css css 2>/dev/null || true
ln -sf public/js js 2>/dev/null || true
ln -sf public/fonts fonts 2>/dev/null || true
ln -sf public/build build 2>/dev/null || true

# Create symlink from public/layout to layout (legacy directory)
rm -f public/layout 2>/dev/null || true
ln -sf ../layout public/layout 2>/dev/null || true
echo "✅ Created symlink: public/layout -> ../layout"

echo ""
echo "📸 Ensuring images are in public/img..."
# If old img/Контент exists at root and public/img/Контент doesn't, copy it
if [ -d "img/Контент" ] && [ ! -L "img" ] && [ ! -d "public/img/Контент" ]; then
    echo "Copying images from root img/ to public/img/"
    cp -r img/Контент public/img/ 2>/dev/null || true
fi

echo ""
echo "✅ Deployment completed successfully!"
echo ""
echo "📝 Note: Frontend assets (public/build/) should be built locally and deployed via git"
echo "   Run locally: npm run build && git add public/build && git commit && git push"
