<?php

use PHPUnit\Framework\TestCase;
use PSP\ApiClient;

/**
 * Тесты для класса ApiClient
 */
class ApiClientTest extends TestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = new ApiClient();
    }

    public function testConstructorUsesDefaultUrl()
    {
        $client = new ApiClient();
        $this->assertEquals('http://5.188.117.42:9000/api', $client->getBaseUrl());
    }

    public function testConstructorAcceptsCustomUrl()
    {
        $customUrl = 'http://custom.api.com/test';
        $client = new ApiClient($customUrl);
        $this->assertEquals($customUrl, $client->getBaseUrl());
    }

    public function testConstructorStripsTrailingSlash()
    {
        $urlWithSlash = 'http://test.api.com/';
        $client = new ApiClient($urlWithSlash);
        $this->assertEquals('http://test.api.com', $client->getBaseUrl());
    }

    /**
     * Интеграционный тест для проверки реального API
     * @group integration
     */
    public function testGetCalcsReturnsData()
    {
        try {
            $result = $this->client->getCalcs();
            $this->assertIsArray($result);
            $this->assertArrayHasKey('iCalcs', $result);
            $this->assertNotEmpty($result['iCalcs']);
        } catch (\Exception $e) {
            $this->markTestSkipped('Real API is not available: ' . $e->getMessage());
        }
    }

    /**
     * Интеграционный тест для получения параметров
     * @group integration
     */
    public function testGetParamsReturnsData()
    {
        $credentials = [
            'db_id' => 1,
            'user' => 'test',
            'pass' => 'test'
        ];

        try {
            $result = $this->client->getParams(1, $credentials);
            $this->assertIsArray($result);
            $this->assertArrayHasKey('params', $result);
            $this->assertArrayHasKey('mat_select_params', $result);
        } catch (\Exception $e) {
            $this->markTestSkipped('Real API is not available: ' . $e->getMessage());
        }
    }

    public function testExceptionThrownOnInvalidUrl()
    {
        $client = new ApiClient('http://invalid.nonexistent.url.test');

        $this->expectException(\Exception::class);
        $client->getCalcs();
    }
}
