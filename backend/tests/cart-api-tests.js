/**
 * PSP Cart API Tests
 * RED → GREEN → REFACTOR approach
 */

const API_BASE = window.location.hostname === 'localhost'
    ? 'http://localhost/backend/api'
    : 'https://psp.realeasystudio.site/backend/api';

// Simple assertion library
function assert(condition, message) {
    if (!condition) {
        throw new Error(message || 'Assertion failed');
    }
}

function assertEquals(actual, expected, message) {
    if (actual !== expected) {
        throw new Error(message || `Expected ${expected}, got ${actual}`);
    }
}

function assertNotNull(value, message) {
    if (value === null || value === undefined) {
        throw new Error(message || 'Value is null or undefined');
    }
}

// Async test wrapper
async function test(name, fn, testCase, suite, runner) {
    const startTime = performance.now();
    try {
        await fn();
        const duration = Math.round(performance.now() - startTime);
        runner.passTest(testCase, duration);
    } catch (error) {
        const duration = Math.round(performance.now() - startTime);
        runner.failTest(testCase, error.message, duration);
    }
}

// Helper to make API requests
async function apiRequest(endpoint, method = 'GET', body = null) {
    const options = {
        method,
        headers: {
            'Content-Type': 'application/json'
        }
    };

    if (body) {
        options.body = JSON.stringify(body);
    }

    const response = await fetch(`${API_BASE}${endpoint}`, options);
    const data = await response.json();

    return { response, data };
}

// ===== TEST SUITES =====

async function testAddCalcWithNewOrder(runner, suite) {
    runner.stats.total++;

    const testCase = runner.addTest(suite, 'addCalc создаёт новый заказ когда calc_id = 0');

    await test('addCalc with calc_id=0', async () => {
        const { response, data } = await apiRequest('/calc/addCalc', 'POST', {
            calc_id: 0,
            calc_position_id: 12345,
            price_good: 5000
        });

        // Проверяем что запрос успешен
        assertEquals(response.status, 200, 'Response status should be 200');

        // Проверяем что вернулся calc_id
        assertNotNull(data.calc_id, 'Response should contain calc_id');
        assert(data.calc_id > 0, 'calc_id should be greater than 0');

        // Проверяем что вернулся calc_position_id
        assertEquals(data.calc_position_id, 12345, 'Response should return same calc_position_id');

    }, testCase, suite, runner);

    runner.updateSuiteStatus(suite);
}

async function testAddCalcWithExistingOrder(runner, suite) {
    runner.stats.total++;

    const testCase = runner.addTest(suite, 'addCalc добавляет товар к существующему заказу');

    await test('addCalc with existing calc_id', async () => {
        // Сначала создаём новый заказ
        const { data: firstItem } = await apiRequest('/calc/addCalc', 'POST', {
            calc_id: 0,
            calc_position_id: 11111,
            price_good: 3000
        });

        const existingCalcId = firstItem.calc_id;
        assertNotNull(existingCalcId, 'First request should return calc_id');

        // Добавляем второй товар к существующему заказу
        const { response, data } = await apiRequest('/calc/addCalc', 'POST', {
            calc_id: existingCalcId,
            calc_position_id: 22222,
            price_good: 4000
        });

        // Проверяем что запрос успешен
        assertEquals(response.status, 200, 'Response status should be 200');

        // Проверяем что calc_id не изменился
        assertEquals(data.calc_id, existingCalcId, 'calc_id should remain the same');

        // Проверяем что вернулся новый calc_position_id
        assertEquals(data.calc_position_id, 22222, 'Response should return new calc_position_id');

    }, testCase, suite, runner);

    runner.updateSuiteStatus(suite);
}

async function testAddCalcValidation(runner, suite) {
    // Test 1: Missing calc_id
    runner.stats.total++;
    let testCase = runner.addTest(suite, 'addCalc возвращает ошибку без calc_id');

    await test('addCalc validation: missing calc_id', async () => {
        const { response, data } = await apiRequest('/calc/addCalc', 'POST', {
            calc_position_id: 12345,
            price_good: 5000
        });

        assertEquals(response.status, 400, 'Should return 400 for missing calc_id');
        assertNotNull(data.error, 'Response should contain error message');

    }, testCase, suite, runner);

    runner.updateSuiteStatus(suite);

    // Test 2: Missing calc_position_id
    runner.stats.total++;
    testCase = runner.addTest(suite, 'addCalc возвращает ошибку без calc_position_id');

    await test('addCalc validation: missing calc_position_id', async () => {
        const { response, data } = await apiRequest('/calc/addCalc', 'POST', {
            calc_id: 0,
            price_good: 5000
        });

        assertEquals(response.status, 400, 'Should return 400 for missing calc_position_id');
        assertNotNull(data.error, 'Response should contain error message');

    }, testCase, suite, runner);

    runner.updateSuiteStatus(suite);

    // Test 3: Missing price_good
    runner.stats.total++;
    testCase = runner.addTest(suite, 'addCalc возвращает ошибку без price_good');

    await test('addCalc validation: missing price_good', async () => {
        const { response, data } = await apiRequest('/calc/addCalc', 'POST', {
            calc_id: 0,
            calc_position_id: 12345
        });

        assertEquals(response.status, 400, 'Should return 400 for missing price_good');
        assertNotNull(data.error, 'Response should contain error message');

    }, testCase, suite, runner);

    runner.updateSuiteStatus(suite);
}

async function testDelCalc(runner, suite) {
    runner.stats.total++;

    const testCase = runner.addTest(suite, 'delCalc удаляет товар из заказа');

    await test('delCalc removes item', async () => {
        // Создаём заказ с товаром
        const { data: addedItem } = await apiRequest('/calc/addCalc', 'POST', {
            calc_id: 0,
            calc_position_id: 99999,
            price_good: 7000
        });

        const calcId = addedItem.calc_id;
        const positionId = addedItem.calc_position_id;

        // Удаляем товар
        const { response, data } = await apiRequest('/calc/delCalc', 'POST', {
            calc_id: calcId,
            calc_position_id: positionId
        });

        // Проверяем успешность
        assertEquals(response.status, 200, 'Response status should be 200');
        assertNotNull(data.success || data.result, 'Response should indicate success');

    }, testCase, suite, runner);

    runner.updateSuiteStatus(suite);
}

// Main test runner
async function runAllTests(runner) {
    console.log('Starting tests...');

    // Suite 1: addCalc endpoint
    const addCalcSuite = runner.createSuite('POST /calc/addCalc');
    await testAddCalcWithNewOrder(runner, addCalcSuite);
    await testAddCalcWithExistingOrder(runner, addCalcSuite);
    await testAddCalcValidation(runner, addCalcSuite);

    // Suite 2: delCalc endpoint
    const delCalcSuite = runner.createSuite('POST /calc/delCalc');
    await testDelCalc(runner, delCalcSuite);

    console.log('All tests completed!');
}
