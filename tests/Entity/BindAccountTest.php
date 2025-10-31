<?php

namespace WechatMiniProgramExpressBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramExpressBundle\Entity\BindAccount;

/**
 * @internal
 */
#[CoversClass(BindAccount::class)]
final class BindAccountTest extends AbstractEntityTestCase
{
    protected function createEntity(): BindAccount
    {
        return new BindAccount();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'valid' => ['valid', true],
            'deliveryId' => ['deliveryId', 'test-delivery-id'],
            'deliveryName' => ['deliveryName', '测试配送公司'],
            'shopId' => ['shopId', 'shop-123'],
            'shopNo' => ['shopNo', 'no-456'],
            'appSecret' => ['appSecret', 'secret-key'],
            'extraConfig' => ['extraConfig', ['config1' => 'value1']],
        ];
    }

    public function testAccountGetterAndSetter(): void
    {
        $entity = $this->createEntity();
        $account = $this->createMock(Account::class);

        $entity->setAccount($account);
        $this->assertSame($account, $entity->getAccount());
    }

    public function testToArray(): void
    {
        $entity = $this->createEntity();
        $deliveryId = 'delivery-123';
        $deliveryName = '测试配送公司';
        $shopId = 'shop-456';
        $shopNo = 'no-789';
        $extraConfig = ['config1' => 'value1', 'config2' => 'value2'];

        $entity->setDeliveryId($deliveryId);
        $entity->setDeliveryName($deliveryName);
        $entity->setShopId($shopId);
        $entity->setShopNo($shopNo);
        $entity->setExtraConfig($extraConfig);

        $array = $entity->toArray();
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

        $entity = $this->createEntity();
        $entity->setDeliveryId($deliveryId);
        $entity->setDeliveryName($deliveryName);
        $entity->setShopId($shopId);

        $array = $entity->retrievePlainArray();
        $this->assertArrayHasKey('deliveryId', $array);
        $this->assertArrayHasKey('deliveryName', $array);
        $this->assertSame($deliveryId, $array['deliveryId']);
    }

    public function testRetrieveApiArray(): void
    {
        $deliveryId = 'delivery-123';
        $deliveryName = '测试配送公司';
        $shopId = 'shop-456';

        $entity = $this->createEntity();
        $entity->setDeliveryId($deliveryId);
        $entity->setDeliveryName($deliveryName);
        $entity->setShopId($shopId);

        $array = $entity->retrieveApiArray();
        // 验证API数组包含需要的字段
        $this->assertArrayHasKey('deliveryId', $array);
        $this->assertArrayHasKey('deliveryName', $array);
    }

    public function testRetrieveAdminArray(): void
    {
        $deliveryId = 'delivery-123';
        $deliveryName = '测试配送公司';
        $shopId = 'shop-456';

        $entity = $this->createEntity();
        $entity->setDeliveryId($deliveryId);
        $entity->setDeliveryName($deliveryName);
        $entity->setShopId($shopId);

        $array = $entity->retrieveAdminArray();
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
            'complex_data' => ['key1' => 'value1', 'key2' => 'value2'],
        ];

        $entity = $this->createEntity();
        $entity->setExtraConfig($extraConfig);

        $retrievedConfig = $entity->getExtraConfig();

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
        $entity = $this->createEntity();
        $idProperty->setValue($entity, 1);

        $entity->setDeliveryName($deliveryName);
        $entity->setShopId($shopId);

        $string = (string) $entity;
        // 检查__toString方法是否将deliveryName和shopId组合起来
        $this->assertStringContainsString($deliveryName, $string);
        $this->assertStringContainsString($shopId, $string);
    }

    public function testTimestampMethods(): void
    {
        $now = new \DateTimeImmutable();

        $entity = $this->createEntity();
        $entity->setCreateTime($now);
        $entity->setUpdateTime($now);

        $this->assertEquals($now, $entity->getCreateTime());
        $this->assertEquals($now, $entity->getUpdateTime());
    }

    public function testIpMethods(): void
    {
        $ip = '192.168.1.1';

        $entity = $this->createEntity();
        $entity->setCreatedFromIp($ip);
        $entity->setUpdatedFromIp($ip);

        $this->assertEquals($ip, $entity->getCreatedFromIp());
        $this->assertEquals($ip, $entity->getUpdatedFromIp());
    }

    public function testUserMethods(): void
    {
        $user = 'testuser';

        $entity = $this->createEntity();
        $entity->setCreatedBy($user);
        $entity->setUpdatedBy($user);

        $this->assertEquals($user, $entity->getCreatedBy());
        $this->assertEquals($user, $entity->getUpdatedBy());
    }
}
