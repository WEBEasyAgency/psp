# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

PSP Calc API is a dual-mode API service for calculator endpoints with a frontend landing page. The project is deployed at `https://psp.realeasystudio.site/` and consists of:

- **Backend API**: PHP-based proxy that can operate in mock mode (returns hardcoded data) or real mode (proxies to remote API at `http://5.188.117.42:9000/api`)
- **Frontend**: Static HTML landing page with minified CSS/JS assets

## Common Commands

### Backend Development

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

# Run local PHP development server (if needed)
cd backend/api
php -S localhost:8000
```

### Testing with cURL

```bash
# Base URL for remote API
BASE_URL="https://psp.realeasystudio.site/backend/api"

# Get list of calculators
curl ${BASE_URL}/calcs

# Get calculator parameters
curl -X POST ${BASE_URL}/1/params \
  -H "Content-Type: application/json" \
  -d '{"db_id": 1, "user": "user", "pass": "password"}'

# Run calculation
curl -X POST ${BASE_URL}/1/run \
  -H "Content-Type: application/json" \
  -d '{"db_id": 1, "user": "user", "pass": "password", "params": [{"id": 54, "variable": "width", "value": 1000}], "mat_select_params": []}'
```

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

```
layout/
├── index.html           # Static landing page
├── css/
│   ├── libs.min.css     # Minified vendor CSS
│   └── app.min.css      # Minified application CSS
├── js/
│   ├── libs.min.js      # Minified vendor JS
│   └── app.min.js       # Minified application JS
├── fonts/               # Inter font family (Regular, Medium, Bold)
└── img/dest/            # Image assets
```

Frontend is a simple static site - no build process required.

## Deployment

Project is automatically synchronized with remote server at `https://psp.realeasystudio.site/`. All code changes are reflected immediately without manual deployment steps.

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

## Migration Status & Future Plans

**Completed:**
- ✅ Real API integration via Guzzle HTTP client (`ApiClient` class)
- ✅ Dual-mode system (mock/real) with runtime switching
- ✅ PHPUnit test coverage for core components

**Planned:**
1. Laravel framework migration (routes → `routes/api.php`, classes → controllers)
2. Database persistence for caching/logging
3. Authentication layer (Laravel Sanctum for API tokens)
4. Request/response logging system
