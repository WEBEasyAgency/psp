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
COMPOSER_PHAR="/usr/local/bin/composer-phar"

echo "✅ PHP version: $($PHP -v | head -1)"

echo ""
echo "📥 Updating code from git..."
git fetch origin main
git reset --hard origin/main

echo ""
echo "📦 Installing/updating Composer dependencies..."
$PHP $COMPOSER_PHAR install --no-interaction --prefer-dist --optimize-autoloader --no-dev

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

# Create symlinks for favicon files
echo "🎨 Creating symlinks for favicon files..."
rm -f favicon.ico favicon.svg apple-touch-icon.png favicon-96x96.png site.webmanifest web-app-manifest-192x192.png web-app-manifest-512x512.png 2>/dev/null || true
ln -sf public/favicon.ico favicon.ico 2>/dev/null || true
ln -sf public/favicon.svg favicon.svg 2>/dev/null || true
ln -sf public/apple-touch-icon.png apple-touch-icon.png 2>/dev/null || true
ln -sf public/favicon-96x96.png favicon-96x96.png 2>/dev/null || true
ln -sf public/site.webmanifest site.webmanifest 2>/dev/null || true
ln -sf public/web-app-manifest-192x192.png web-app-manifest-192x192.png 2>/dev/null || true
ln -sf public/web-app-manifest-512x512.png web-app-manifest-512x512.png 2>/dev/null || true
echo "✅ Created favicon symlinks"

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
