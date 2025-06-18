<?php

namespace WechatMiniProgramExpressBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Entity\DeliveryCompany;

class DeliveryCompanyTest extends TestCase
{
    private DeliveryCompany $deliveryCompany;

    protected function setUp(): void
    {
        $this->deliveryCompany = new DeliveryCompany();
    }

    public function testGettersAndSetters(): void
    {
        $deliveryId = 'test-delivery-id';
        $deliveryName = '测试配送公司';

        $this->deliveryCompany->setDeliveryId($deliveryId);
        $this->deliveryCompany->setDeliveryName($deliveryName);
        $this->deliveryCompany->setValid(true);

        $this->assertSame($deliveryId, $this->deliveryCompany->getDeliveryId());
        $this->assertSame($deliveryName, $this->deliveryCompany->getDeliveryName());
        $this->assertTrue($this->deliveryCompany->isValid());
    }

    public function testToArray(): void
    {
        $deliveryId = 'test-delivery-id';
        $deliveryName = '测试配送公司';

        $this->deliveryCompany->setDeliveryId($deliveryId);
        $this->deliveryCompany->setDeliveryName($deliveryName);

        $array = $this->deliveryCompany->toArray();
        $this->assertArrayHasKey('deliveryId', $array);
        $this->assertArrayHasKey('deliveryName', $array);
        $this->assertSame($deliveryId, $array['deliveryId']);
        $this->assertSame($deliveryName, $array['deliveryName']);
    }

    public function testRetrievePlainArray(): void
    {
        $deliveryId = 'test-delivery-id';
        $deliveryName = '测试配送公司';

        $this->deliveryCompany->setDeliveryId($deliveryId);
        $this->deliveryCompany->setDeliveryName($deliveryName);

        $array = $this->deliveryCompany->retrievePlainArray();
        $this->assertArrayHasKey('deliveryId', $array);
        $this->assertArrayHasKey('deliveryName', $array);
        $this->assertSame($deliveryId, $array['deliveryId']);
        $this->assertSame($deliveryName, $array['deliveryName']);
    }

    public function testRetrieveApiArray(): void
    {
        $deliveryId = 'test-delivery-id';
        $deliveryName = '测试配送公司';

        $this->deliveryCompany->setDeliveryId($deliveryId);
        $this->deliveryCompany->setDeliveryName($deliveryName);

        $array = $this->deliveryCompany->retrieveApiArray();
        // 验证API数组中包含的字段
        $this->assertArrayHasKey('deliveryId', $array);
        $this->assertArrayHasKey('deliveryName', $array);
    }

    public function testRetrieveAdminArray(): void
    {
        $deliveryId = 'test-delivery-id';
        $deliveryName = '测试配送公司';

        $this->deliveryCompany->setDeliveryId($deliveryId);
        $this->deliveryCompany->setDeliveryName($deliveryName);

        $array = $this->deliveryCompany->retrieveAdminArray();
        // 验证管理员数组中包含的字段
        $this->assertArrayHasKey('deliveryId', $array);
        $this->assertArrayHasKey('deliveryName', $array);
    }

    public function testStringable(): void
    {
        $deliveryId = 'test-delivery-id';
        $deliveryName = '测试配送公司';

        // 设置ID，因为__toString方法可能在ID为空时返回空字符串
        $reflectionClass = new \ReflectionClass(DeliveryCompany::class);
        $idProperty = $reflectionClass->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($this->deliveryCompany, 1);

        $this->deliveryCompany->setDeliveryId($deliveryId);
        $this->deliveryCompany->setDeliveryName($deliveryName);
        
        // 测试__toString方法
        $this->assertIsString((string) $this->deliveryCompany);
        $this->assertStringContainsString($deliveryName, (string) $this->deliveryCompany);
    }
} 