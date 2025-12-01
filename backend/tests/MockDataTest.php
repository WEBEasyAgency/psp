<?php

use PHPUnit\Framework\TestCase;
use PSP\MockData;

/**
 * Тесты для класса MockData
 */
class MockDataTest extends TestCase
{
    protected function setUp(): void
    {
        // Убеждаемся, что используется mock режим
        MockData::disableRealApi();
    }

    public function testGetCalcsReturnsList()
    {
        $result = MockData::getCalcs();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('iCalcs', $result);
        $this->assertIsArray($result['iCalcs']);
        $this->assertGreaterThan(0, count($result['iCalcs']));
    }

    public function testGetCalcsStructure()
    {
        $result = MockData::getCalcs();
        $firstCalc = $result['iCalcs'][0];

        $this->assertArrayHasKey('id', $firstCalc);
        $this->assertArrayHasKey('name', $firstCalc);
        $this->assertIsInt($firstCalc['id']);
        $this->assertIsString($firstCalc['name']);
    }

    public function testGetParamsReturnsCorrectStructure()
    {
        $result = MockData::getParams(1);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('params', $result);
        $this->assertArrayHasKey('mat_select_params', $result);
        $this->assertIsArray($result['params']);
        $this->assertIsArray($result['mat_select_params']);
    }

    public function testGetParamsContainsValidData()
    {
        $result = MockData::getParams(1);
        $firstParam = $result['params'][0];

        $this->assertArrayHasKey('id', $firstParam);
        $this->assertArrayHasKey('variable', $firstParam);
        $this->assertArrayHasKey('caption', $firstParam);
        $this->assertArrayHasKey('type', $firstParam);
        $this->assertArrayHasKey('default', $firstParam);
        $this->assertArrayHasKey('value', $firstParam);
    }

    public function testRunCalculationReturnsResult()
    {
        $input = [
            'db_id' => 1,
            'user' => 'test',
            'pass' => 'test',
            'params' => [
                ['id' => 54, 'variable' => 'width', 'value' => 1000],
                ['id' => 55, 'variable' => 'height', 'value' => 500]
            ],
            'mat_select_params' => []
        ];

        $result = MockData::runCalculation(1, $input);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('calc_position_id', $result);
        $this->assertArrayHasKey('price_good', $result);
        $this->assertIsInt($result['calc_position_id']);
        $this->assertIsNumeric($result['price_good']);
    }

    public function testRunCalculationWithExistingId()
    {
        $input = [
            'db_id' => 1,
            'user' => 'test',
            'pass' => 'test',
            'calc_position_id' => 12345,
            'params' => [],
            'mat_select_params' => []
        ];

        $result = MockData::runCalculation(1, $input);

        $this->assertEquals(12345, $result['calc_position_id']);
    }

    public function testAddToCalculationCreatesNewId()
    {
        $input = [
            'db_id' => 1,
            'user' => 'test',
            'pass' => 'test',
            'calc_id' => 0,
            'calc_position_id' => 48490,
            'price_good' => 1260.00
        ];

        $result = MockData::addToCalculation($input);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('calc_id', $result);
        $this->assertGreaterThan(0, $result['calc_id']);
    }

    public function testAddToCalculationKeepsExistingId()
    {
        $input = [
            'db_id' => 1,
            'user' => 'test',
            'pass' => 'test',
            'calc_id' => 999999,
            'calc_position_id' => 48490,
            'price_good' => 1260.00
        ];

        $result = MockData::addToCalculation($input);

        $this->assertEquals(999999, $result['calc_id']);
    }

    public function testSaveCalculationReturnsOk()
    {
        $input = [
            'db_id' => 1,
            'user' => 'test',
            'pass' => 'test',
            'calc__id' => 41008167,
            'clientId' => 988
        ];

        $result = MockData::saveCalculation($input);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('result', $result);
        $this->assertEquals('OK', $result['result']);
    }

    public function testDeleteCalculationReturnsOk()
    {
        $input = [
            'db_id' => 1,
            'user' => 'test',
            'pass' => 'test',
            'calc_position_id' => 48490
        ];

        $result = MockData::deleteCalculation($input);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('status', $result);
        $this->assertEquals('OK', $result['status']);
    }

    public function testAddLinkReturnsOk()
    {
        $input = [
            'db_id' => 1,
            'user' => 'test',
            'pass' => 'test',
            'calc_id' => 4849,
            'link' => 'https://disk.yandex.ru/d/hPPhCHJidDlptQ'
        ];

        $result = MockData::addLink($input);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('result', $result);
        $this->assertEquals('OK', $result['result']);
    }

    public function testAddContactReturnsOk()
    {
        $input = [
            'db_id' => 1,
            'user' => 'test',
            'pass' => 'test',
            'fio' => 'Петров Иван Семёнович',
            'phone' => '(846) 925-46-17',
            'email' => 'petrovivan@mail.ru'
        ];

        $result = MockData::addContact($input);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('status', $result);
        $this->assertEquals('OK', $result['status']);
    }

    public function testMockModeIsEnabledByDefault()
    {
        $this->assertFalse(MockData::isUsingRealApi());
    }

    public function testCanEnableRealApi()
    {
        MockData::enableRealApi('http://test.api');
        $this->assertTrue(MockData::isUsingRealApi());

        // Cleanup
        MockData::disableRealApi();
    }

    public function testCanDisableRealApi()
    {
        MockData::enableRealApi();
        MockData::disableRealApi();

        $this->assertFalse(MockData::isUsingRealApi());
    }
}
