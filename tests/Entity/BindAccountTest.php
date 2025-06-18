<?php

namespace WechatMiniProgramExpressBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramExpressBundle\Entity\BindAccount;

class BindAccountTest extends TestCase
{
    private BindAccount $bindAccount;

    protected function setUp(): void
    {
        $this->bindAccount = new BindAccount();
    }

    public function testGettersAndSetters(): void
    {
        $account = $this->createMock(Account::class);
        $deliveryId = 'delivery-123';
        $deliveryName = '测试配送公司';
        $shopId = 'shop-456';
        $shopNo = 'no-789';
        $appSecret = 'secret-key';
        $extraConfig = ['config1' => 'value1', 'config2' => 'value2'];

        $this->bindAccount->setAccount($account);
        $this->bindAccount->setDeliveryId($deliveryId);
        $this->bindAccount->setDeliveryName($deliveryName);
        $this->bindAccount->setShopId($shopId);
        $this->bindAccount->setShopNo($shopNo);
        $this->bindAccount->setAppSecret($appSecret);
        $this->bindAccount->setExtraConfig($extraConfig);
        $this->bindAccount->setValid(true);

        $this->assertSame($account, $this->bindAccount->getAccount());
        $this->assertSame($deliveryId, $this->bindAccount->getDeliveryId());
        $this->assertSame($deliveryName, $this->bindAccount->getDeliveryName());
        $this->assertSame($shopId, $this->bindAccount->getShopId());
        $this->assertSame($shopNo, $this->bindAccount->getShopNo());
        $this->assertSame($appSecret, $this->bindAccount->getAppSecret());
        $this->assertSame($extraConfig, $this->bindAccount->getExtraConfig());
        $this->assertTrue($this->bindAccount->isValid());
    }

    public function testToArray(): void
    {
        $deliveryId = 'delivery-123';
        $deliveryName = '测试配送公司';
        $shopId = 'shop-456';
        $shopNo = 'no-789';
        $extraConfig = ['config1' => 'value1', 'config2' => 'value2'];

        $this->bindAccount->setDeliveryId($deliveryId);
        $this->bindAccount->setDeliveryName($deliveryName);
        $this->bindAccount->setShopId($shopId);
        $this->bindAccount->setShopNo($shopNo);
        $this->bindAccount->setExtraConfig($extraConfig);

        $array = $this->bindAccount->toArray();
        $this->assertArrayHasKey('deliveryId', $array);
        $this->assertArrayHasKey('deliveryName', $array);
        $this->assertArrayHasKey('shopId', $array);
        $this->assertArrayHasKey('shopNo', $array);
        $this->assertArrayHasKey('extraConfig', $array);
        $this->assertSame($deliveryId, $array['deliveryId']);
        $this->assertSame($deliveryName, $array['deliveryName']);
        $this->assertSame($shopId, $array['shopId']);
        $this->assertSame($shopNo, $array['shopNo']);
        $this->assertSame($extraConfig, $array['extraConfig']);
    }

    public function testRetrievePlainArray(): void
    {
        $deliveryId = 'delivery-123';
        $deliveryName = '测试配送公司';
        $shopId = 'shop-456';

        $this->bindAccount->setDeliveryId($deliveryId);
        $this->bindAccount->setDeliveryName($deliveryName);
        $this->bindAccount->setShopId($shopId);

        $array = $this->bindAccount->retrievePlainArray();
        $this->assertArrayHasKey('deliveryId', $array);
        $this->assertArrayHasKey('deliveryName', $array);
        $this->assertSame($deliveryId, $array['deliveryId']);
    }

    public function testRetrieveApiArray(): void
    {
        $deliveryId = 'delivery-123';
        $deliveryName = '测试配送公司';
        $shopId = 'shop-456';

        $this->bindAccount->setDeliveryId($deliveryId);
        $this->bindAccount->setDeliveryName($deliveryName);
        $this->bindAccount->setShopId($shopId);

        $array = $this->bindAccount->retrieveApiArray();
        // 验证API数组包含需要的字段
        $this->assertArrayHasKey('deliveryId', $array);
        $this->assertArrayHasKey('deliveryName', $array);
    }

    public function testRetrieveAdminArray(): void
    {
        $deliveryId = 'delivery-123';
        $deliveryName = '测试配送公司';
        $shopId = 'shop-456';

        $this->bindAccount->setDeliveryId($deliveryId);
        $this->bindAccount->setDeliveryName($deliveryName);
        $this->bindAccount->setShopId($shopId);

        $array = $this->bindAccount->retrieveAdminArray();
        // 验证管理员数组包含需要的字段
        $this->assertArrayHasKey('deliveryId', $array);
        $this->assertArrayHasKey('deliveryName', $array);
        $this->assertArrayHasKey('shopId', $array);
    }

    public function testExtraConfigJsonSerialization(): void
    {
        $extraConfig = [
            'delivery_service' => 1,
            'audit_result' => 2,
            'complex_data' => ['key1' => 'value1', 'key2' => 'value2']
        ];

        $this->bindAccount->setExtraConfig($extraConfig);

        $retrievedConfig = $this->bindAccount->getExtraConfig();

        $this->assertSame($extraConfig, $retrievedConfig);
        $this->assertArrayHasKey('delivery_service', $retrievedConfig);
        $this->assertArrayHasKey('audit_result', $retrievedConfig);
        $this->assertArrayHasKey('complex_data', $retrievedConfig);
        $this->assertSame(1, $retrievedConfig['delivery_service']);
        $this->assertSame(2, $retrievedConfig['audit_result']);
    }

    public function testStringable(): void
    {
        $deliveryName = '测试配送公司';
        $shopId = 'shop-456';

        // 设置ID，因为__toString方法可能在ID为空时返回空字符串
        $reflectionClass = new \ReflectionClass(BindAccount::class);
        $idProperty = $reflectionClass->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($this->bindAccount, 1);

        $this->bindAccount->setDeliveryName($deliveryName);
        $this->bindAccount->setShopId($shopId);
        
        $string = (string) $this->bindAccount;
        // 检查__toString方法是否将deliveryName和shopId组合起来
        $this->assertStringContainsString($deliveryName, $string);
        $this->assertStringContainsString($shopId, $string);
    }

    public function testTimestampMethods(): void
    {
        $now = new \DateTimeImmutable();
        
        $this->bindAccount->setCreateTime($now);
        $this->bindAccount->setUpdateTime($now);
        
        $this->assertEquals($now, $this->bindAccount->getCreateTime());
        $this->assertEquals($now, $this->bindAccount->getUpdateTime());
    }

    public function testIpMethods(): void
    {
        $ip = '192.168.1.1';
        
        $this->bindAccount->setCreatedFromIp($ip);
        $this->bindAccount->setUpdatedFromIp($ip);
        
        $this->assertEquals($ip, $this->bindAccount->getCreatedFromIp());
        $this->assertEquals($ip, $this->bindAccount->getUpdatedFromIp());
    }

    public function testUserMethods(): void
    {
        $user = 'testuser';
        
        $this->bindAccount->setCreatedBy($user);
        $this->bindAccount->setUpdatedBy($user);
        
        $this->assertEquals($user, $this->bindAccount->getCreatedBy());
        $this->assertEquals($user, $this->bindAccount->getUpdatedBy());
    }
} 