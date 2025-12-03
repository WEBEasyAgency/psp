# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

PSP Calc API is a Laravel 12 + Vue 3 application for online calculator services. The project is deployed at `https://psp.realeasystudio.site/` and consists of:

- **Backend API**: PHP-based proxy that can operate in mock mode (returns hardcoded data) or real mode (proxies to remote API at `http://5.188.117.42:9000/api`)
- **Frontend**: Laravel 12 + Vue 3 application with Vite build system
- **Legacy Assets**: Original static HTML/CSS/JS from `/layout` directory (still accessible)

## Common Commands

### Laravel + Vue Development

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Build frontend assets for production
npm run build

# Development build with hot reload
npm run dev

# Generate application key (after .env setup)
php artisan key:generate

# Clear Laravel caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Backend Development (Legacy API in /backend)

```bash
# Install PHP dependencies (includes Guzzle HTTP client and PHPUnit)
cd backend
composer install

# Run tests
cd backend
vendor/bin/phpunit

# Run specific test class
vendor/bin/phpunit tests/ApiClientTest.php

# Test API endpoint remotely
curl https://psp.realeasystudio.site/backend/api/calcs
```

### Testing with cURL

```bash
# Base URL for remote API
BASE_URL="https://psp.realeasystudio.site/backend/api"

# Get list of calculators
curl ${BASE_URL}/calcs

# Get calculator parameters (note the /calc/ prefix)
curl -X POST ${BASE_URL}/calc/146/params \
  -H "Content-Type: application/json" \
  -d '{"db_id": 1, "user": "user", "pass": "password"}'

# Run calculation
curl -X POST ${BASE_URL}/calc/146/run \
  -H "Content-Type: application/json" \
  -d '{"db_id": 1, "user": "user", "pass": "password", "params": [{"variable": "w", "type": 1, "value": 1.5}], "mat_select_params": []}'
```

**Available Calculator IDs:**
- **146**: Объемные буквы с бортом из алюминия
- **155**: Объемные буквы со световым бортом
- **156**: Пластиковые вывески
- **157**: Акриловые вывески
- **158**: Вывески из композита
- **151**: Стенд из пластика с карманами
- **154**: Маленькие наклейки
- **159**: Пластиковые таблички
- **160**: Акриловые таблички
- **161**: Таблички из композита

### Postman Testing

Import `backend/api/PSP_Calc_API.postman_collection.json` and set environment variable:
- `base_url`: `https://psp.realeasystudio.site/backend/api`

## Architecture

### Backend API Structure

```
backend/
├── api/
│   ├── index.php          # Main router with endpoint definitions
│   ├── config.php         # API mode configuration (mock/real)
│   ├── .htaccess          # Apache rewrite rules for clean URLs
│   └── src/
│       ├── Router.php     # Custom router class handling GET/POST routes
│       ├── Response.php   # JSON response helper
│       ├── MockData.php   # Mock data provider; switches to real API when configured
│       └── ApiClient.php  # Guzzle HTTP client for remote API integration
├── tests/                 # PHPUnit test suite
│   ├── RouterTest.php
│   ├── MockDataTest.php
│   └── ApiClientTest.php
├── composer.json          # PSR-4 autoloading: PSP\ namespace maps to api/src/
├── phpunit.xml            # PHPUnit configuration
├── RestApi.yaml           # OpenAPI 3.1.0 specification (original)
└── RestApi New.yaml       # Extended specification with new endpoints
```

#### Request Flow

1. Apache `.htaccess` routes all requests to `index.php`
2. `config.php` loaded to determine API mode (mock/real)
3. If real mode: `MockData::enableRealApi()` configures `ApiClient` with remote URL
4. `Router` class parses URL path (strips `/backend/api` or `/api` prefix)
5. Pattern matching with regex extracts route parameters (e.g., calculator ID)
6. Request body validated against required fields
7. `MockData` static methods either return hardcoded data OR proxy to `ApiClient`
8. `Response` class outputs JSON with appropriate headers (CORS enabled)

#### API Mode Configuration

The API operates in two modes controlled by `backend/api/config.php`:

- **Mock mode** (default): Returns hardcoded responses from `MockData` class
- **Real mode**: Proxies requests to remote API using `ApiClient` (Guzzle HTTP)

Switch modes by setting environment variable `API_MODE=real` or editing config.php directly.
The real API URL defaults to `http://5.188.117.42:9000/api` (from OpenAPI spec).

#### Key Routing Patterns

**Original endpoints:**
- `GET /calcs` → List all calculators
- `POST /:id/params` → Get calculator parameters (requires: db_id, user, pass)
- `POST /:id/run` → Execute calculation (requires: db_id, user, pass, params, mat_select_params)

**New endpoints (from RestApi New.yaml):**
- `POST /addCalc` → Add calculation to cart (requires: db_id, user, pass, calc_position_id, price_good)
- `POST /delCalc` → Delete calculation (requires: db_id, user, pass, calc_position_id)
- `POST /saveCalc` → Save calculation (requires: db_id, user, pass, calc__id, clientId)
- `POST /addLink` → Add file link to calculation (requires: db_id, user, pass, calc_id, link)
- `POST /addContact` → Add client contact (requires: db_id, user, pass, fio; optional: phone, email)

All POST endpoints validate required fields and return 400 errors if missing.

### Frontend Structure

**Laravel + Vue 3 Application:**

```
resources/
├── js/
│   ├── pages/                    # Page entry points
│   │   ├── welcome.js            # Welcome page
│   │   ├── order.js              # Order page
│   │   ├── thanx.js              # Thank you page
│   │   ├── product-146.js        # Объемные буквы с бортом из алюминия
│   │   ├── product-155.js        # Объемные буквы со световым бортом
│   │   ├── product-156.js        # Пластиковые вывески
│   │   ├── product-157.js        # Акриловые вывески
│   │   ├── product-158.js        # Вывески из композита
│   │   ├── product-151.js        # Стенд из пластика с карманами
│   │   ├── product-154.js        # Маленькие наклейки
│   │   ├── product-159.js        # Пластиковые таблички
│   │   ├── product-160.js        # Акриловые таблички
│   │   └── product-161.js        # Таблички из композита
│   ├── widgets/product/calculators/  # Calculator Vue components
│   │   ├── Calc146.vue           # Объемные буквы с бортом
│   │   ├── Calc155.vue           # Объемные буквы со световым бортом
│   │   ├── Calc156.vue           # Пластиковые вывески
│   │   ├── Calc157.vue           # Акриловые вывески
│   │   ├── Calc158.vue           # Композитные вывески
│   │   ├── Calc151.vue           # Стенд с карманами
│   │   ├── Calc154.vue           # Наклейки
│   │   ├── Calc159.vue           # Пластиковые таблички
│   │   ├── Calc160.vue           # Акриловые таблички
│   │   └── Calc161.vue           # Композитные таблички
│   ├── entities/product/ui/      # Shared product page components
│   │   ├── Header.vue            # Navigation header
│   │   ├── Footer.vue            # Footer
│   │   ├── TechnologyAdvantages.vue
│   │   ├── InstallationCases.vue # Image slider with installation examples
│   │   ├── Faq.vue
│   │   ├── SeoBlock.vue
│   │   ├── Feedback.vue
│   │   └── PopupMenu.vue
│   ├── shared/ui/                # Reusable UI components
│   │   ├── NumberInput.vue
│   │   ├── FilterButtons.vue
│   │   ├── ToggleSwitch.vue
│   │   ├── RadioGroup.vue
│   │   └── Button.vue
│   └── main.css                  # Global styles
├── views/
│   ├── welcome.blade.php         # Welcome page template
│   ├── order.blade.php           # Order page template
│   ├── thanx.blade.php           # Thank you page template
│   └── product.blade.php         # Generic product/calculator template
└── css/
    ├── app.css
    └── product.css

routes/
└── web.php                       # Route definitions with calculator metadata

public/
├── build/                        # Vite compiled assets (auto-generated)
└── img/
    ├── Контент/Контент/          # Product images organized by category
    │   ├── 1а Объемные буквы с бортом из алюминия/
    │   ├── 1б Объемные буквы со световым бортом/
    │   ├── 2а Пластиковые вывески/
    │   ├── 2б Акриловые вывески/
    │   ├── 2в Плоские вывески из композита/
    │   ├── 3а Стенд из пластика с карманами/
    │   ├── 4а Маленькие наклейки с резкой/
    │   ├── 5а Пластиковые таблички/
    │   ├── 5б Акриловые таблички/
    │   ├── 5в Таблички из композита/
    │   ├── 6а Баннер с люверсами/
    │   ├── 7а Режим работы акриловый Премиум/
    │   ├── 7б Режим работы пластиковый/
    │   └── 7в Режим работы (наклейка)/
    └── dest/                     # General assets

layout/                           # Legacy static site (still accessible)
├── index-new.html                # Current static homepage (temporary redirect)
├── css/, js/, fonts/, img/       # Static assets
```

**Key Frontend Concepts:**

1. **Calculator Architecture**: Each calculator (146-161) has:
   - Dedicated Vue component (`Calc{ID}.vue`) with business logic
   - Dedicated entry point (`product-{ID}.js`) for Vite
   - Route definition in `routes/web.php` with title and image gallery
   - All share the same Blade template (`product.blade.php`)

2. **Calculator Component Structure**:
   ```javascript
   // Each Calc component follows this pattern:
   - Reactive data for form inputs
   - API call to /backend/api/calc/{ID}/params for field definitions
   - API call to /backend/api/calc/{ID}/run for price calculation
   - Type mapping: 1=numeric, 2=boolean, 5=select/options
   ```

3. **Image Path Convention**: All images must use absolute paths starting with `/img/...`
   - Correct: `:src="'/img/Контент/Контент/...'"`
   - Incorrect: `:src="'img/Контент/Контент/...'"` (relative path fails)

4. **Symlink Structure**: `deploy.sh` creates symlinks from root to `public/` directory:
   ```bash
   img -> public/img
   css -> public/css
   js -> public/js
   fonts -> public/fonts
   build -> public/build
   ```

5. **Build Process**:
   - Run `npm run build` locally before deployment
   - Vite generates hashed assets in `public/build/`
   - Commit built assets to git for deployment
   - GitHub Actions auto-deploys on push to main

## Deployment

### Remote Server Environment (BeGet Hosting)

**Important:** BeGet shared hosting has specific limitations and requirements:

- **PHP CLI Default**: 5.6.40 (too old for Laravel)
- **PHP 8.4 Path**: `/usr/local/bin/php8.4` (must be used explicitly)
- **Composer**: Installed locally at `~/bin/composer` (version 2.9.2)
- **Node.js**: May not be available - build frontend locally
- **Document Root**: `/home/a/abrobe14/psp.realeasystudio.site/public_html`
- **Web PHP**: 8.4.6 (configured in control panel)

### SSH Access

```bash
# Connect to remote server
ssh abrobe14_psp@psp.realeasystudio.site

# Note: You're automatically in public_html directory
pwd  # Output: /home/a/abrobe14/psp.realeasystudio.site/public_html
```

### Deployment Workflow

#### Option 1: Manual Deployment via deploy.sh

```bash
# On remote server via SSH
ssh abrobe14_psp@psp.realeasystudio.site
bash deploy.sh
```

The `deploy.sh` script automatically:
1. Pulls latest code from git (`git pull origin main`)
2. Installs/updates Composer dependencies with PHP 8.4
3. Clears Laravel caches
4. Verifies storage permissions

**Note:** Frontend assets (`public/build/`) must be built locally and committed to git.

#### Option 2: Automated Deployment (Recommended)

Create a GitHub Action workflow (`.github/workflows/deploy.yml`):

```yaml
name: Deploy to Production

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3

      - name: Setup Node.js
        uses: actions/setup-node@v3
        with:
          node-version: '20'

      - name: Build frontend
        run: |
          npm ci
          npm run build

      - name: Commit built assets
        run: |
          git config user.name "GitHub Actions"
          git config user.email "actions@github.com"
          git add public/build -f
          git commit -m "Build frontend assets" || echo "No changes"
          git push

      - name: Deploy to server
        uses: appleboy/ssh-action@master
        with:
          host: psp.realeasystudio.site
          username: abrobe14_psp
          key: ${{ secrets.SSH_PRIVATE_KEY }}
          script: |
            cd /home/a/abrobe14/psp.realeasystudio.site/public_html
            bash deploy.sh
```

#### Option 3: Manual Deployment Steps

```bash
# 1. Local: Build frontend
npm run build

# 2. Local: Commit and push (including built assets if not in .gitignore)
git add .
git commit -m "Your changes"
git push origin main

# 3. Remote: Pull and deploy
ssh abrobe14_psp@psp.realeasystudio.site 'bash deploy.sh'

# Or step-by-step:
ssh abrobe14_psp@psp.realeasystudio.site
git pull origin main
/usr/local/bin/php8.4 ~/bin/composer install --no-dev --optimize-autoloader
/usr/local/bin/php8.4 artisan config:clear
```

### Running PHP Commands on Remote Server

Always use PHP 8.4 explicitly on BeGet:

```bash
# ❌ Wrong - uses PHP 5.6
php artisan migrate

# ✅ Correct - uses PHP 8.4
/usr/local/bin/php8.4 artisan migrate

# ❌ Wrong - uses PHP 5.6 with Composer
composer install

# ✅ Correct - uses PHP 8.4 with Composer
/usr/local/bin/php8.4 ~/bin/composer install
```

### Important Configuration Notes

1. **Session Driver**: Set to `file` in `.env` (not database) because SQLite is not configured
   ```bash
   SESSION_DRIVER=file
   ```

2. **Application Key**: Must be generated on remote server
   ```bash
   /usr/local/bin/php8.4 artisan key:generate
   ```

3. **Laravel Entry Point**: Root `index.php` proxies to `public/index.php`
   ```php
   <?php
   require __DIR__.'/public/index.php';
   ```

4. **Git Ignore**: `vendor/` and `node_modules/` are in `.gitignore` (installed on server)
   `public/build/` can be either committed or deployed separately via rsync

### Troubleshooting Deployment

**Problem:** "No application encryption key"
```bash
ssh abrobe14_psp@psp.realeasystudio.site
/usr/local/bin/php8.4 artisan key:generate
```

**Problem:** Composer shows PHP 5.6 errors
```bash
# Use explicit PHP 8.4 path
/usr/local/bin/php8.4 ~/bin/composer install
```

**Problem:** Frontend not updating
```bash
# Build locally and commit
npm run build
git add public/build
git commit -m "Update frontend assets"
git push

# Or rsync directly
rsync -avz public/build/ abrobe14_psp@psp.realeasystudio.site:public/build/
```

## API Specification

Two OpenAPI 3.1.0 specifications are available:
- `RestApi.yaml` - Original specification with core calculator endpoints
- `RestApi New.yaml` - Extended specification including new cart/save endpoints

Reference these for:
- Request/response schemas
- Required vs optional fields
- Data types and validation rules

## Testing

PHPUnit test suite is located in `backend/tests/`:
- `RouterTest.php` - Tests routing logic and path parsing
- `MockDataTest.php` - Tests mock data generation
- `ApiClientTest.php` - Tests Guzzle HTTP client integration

Run tests from `backend/` directory using `vendor/bin/phpunit`.

## Current Project Status

**Live Pages:**
- https://psp.realeaststudio.site/ → Redirects to `/layout/index-new.html` (temporary)
- https://psp.realeasystudio.site/product/146 - Объемные буквы с бортом из алюминия
- https://psp.realeasystudio.site/product/155 - Объемные буквы со световым бортом
- https://psp.realeasystudio.site/product/156 - Пластиковые вывески
- https://psp.realeasystudio.site/product/157 - Акриловые вывески
- https://psp.realeasystudio.site/product/158 - Вывески из композита
- https://psp.realeasystudio.site/product/151 - Стенд из пластика с карманами
- https://psp.realeasystudio.site/product/154 - Маленькие наклейки
- https://psp.realeasystudio.site/product/159 - Пластиковые таблички
- https://psp.realeasystudio.site/product/160 - Акриловые таблички
- https://psp.realeasystudio.site/product/161 - Таблички из композита
- https://psp.realeasystudio.site/order - Order page
- https://psp.realeaststudio.site/thanx - Thank you page

**Deployment:**
- GitHub Actions configured for automatic deployment on push to main
- Builds frontend assets and triggers `deploy.sh` on server
- All images served via symlink structure from `public/img/`

## Migration Status & Future Plans

**Completed:**
- ✅ Real API integration via Guzzle HTTP client (`ApiClient` class)
- ✅ Dual-mode system (mock/real) with runtime switching
- ✅ PHPUnit test coverage for core components
- ✅ 10 calculator pages implemented with Vue 3 components
- ✅ Image galleries configured for all calculator pages
- ✅ InstallationCases slider with category-based images
- ✅ Order and Thank You pages
- ✅ Automated deployment via GitHub Actions

**Planned:**
1. Replace temporary homepage redirect with permanent Vue-based homepage
2. Laravel framework migration (routes → `routes/api.php`, classes → controllers)
3. Database persistence for caching/logging
4. Authentication layer (Laravel Sanctum for API tokens)
5. Request/response logging system

## Common Issues & Solutions

**Issue: Images not loading (404 errors)**
- **Cause**: Relative paths (`img/...`) instead of absolute paths (`/img/...`)
- **Solution**: Always use absolute paths starting with `/` in Vue templates
- **Check**: `deploy.sh` creates correct symlinks: `img -> public/img`

**Issue: Calculator not fetching parameters**
- **Cause**: Wrong API endpoint format (missing `/calc/` prefix)
- **Solution**: Use `/backend/api/calc/{ID}/params` not `/backend/api/{ID}/params`

**Issue: Changes not appearing on live site**
- **Cause**: Frontend assets not rebuilt or GitHub Actions failed
- **Solution**: Run `npm run build` locally, commit `public/build/`, and push
- **Verify**: Check GitHub Actions workflow status

**Issue: Symlinks broken after deployment**
- **Cause**: Recursive symlinks or incorrect symlink direction
- **Solution**: `deploy.sh` removes old symlinks and creates fresh ones
- **Command**: `ssh ... 'bash deploy.sh'` to manually trigger
