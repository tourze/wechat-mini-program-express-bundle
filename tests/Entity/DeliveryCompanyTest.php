<?php

namespace WechatMiniProgramExpressBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramExpressBundle\Entity\DeliveryCompany;

/**
 * @internal
 */
#[CoversClass(DeliveryCompany::class)]
final class DeliveryCompanyTest extends AbstractEntityTestCase
{
    protected function createEntity(): DeliveryCompany
    {
        return new DeliveryCompany();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'deliveryId' => ['deliveryId', 'test-delivery-id'],
            'deliveryName' => ['deliveryName', '测试配送公司'],
            'valid' => ['valid', true],
        ];
    }

    public function testToArray(): void
    {
        $deliveryId = 'test-delivery-id';
        $deliveryName = '测试配送公司';

        $entity = $this->createEntity();
        $entity->setDeliveryId($deliveryId);
        $entity->setDeliveryName($deliveryName);

        $array = $entity->toArray();
        $this->assertArrayHasKey('deliveryId', $array);
        $this->assertArrayHasKey('deliveryName', $array);
        $this->assertSame($deliveryId, $array['deliveryId']);
        $this->assertSame($deliveryName, $array['deliveryName']);
    }

    public function testRetrievePlainArray(): void
    {
        $deliveryId = 'test-delivery-id';
        $deliveryName = '测试配送公司';

        $entity = $this->createEntity();
        $entity->setDeliveryId($deliveryId);
        $entity->setDeliveryName($deliveryName);

        $array = $entity->retrievePlainArray();
        $this->assertArrayHasKey('deliveryId', $array);
        $this->assertArrayHasKey('deliveryName', $array);
        $this->assertSame($deliveryId, $array['deliveryId']);
        $this->assertSame($deliveryName, $array['deliveryName']);
    }

    public function testRetrieveApiArray(): void
    {
        $deliveryId = 'test-delivery-id';
        $deliveryName = '测试配送公司';

        $entity = $this->createEntity();
        $entity->setDeliveryId($deliveryId);
        $entity->setDeliveryName($deliveryName);

        $array = $entity->retrieveApiArray();
        // 验证API数组中包含的字段
        $this->assertArrayHasKey('deliveryId', $array);
        $this->assertArrayHasKey('deliveryName', $array);
    }

    public function testRetrieveAdminArray(): void
    {
        $deliveryId = 'test-delivery-id';
        $deliveryName = '测试配送公司';

        $entity = $this->createEntity();
        $entity->setDeliveryId($deliveryId);
        $entity->setDeliveryName($deliveryName);

        $array = $entity->retrieveAdminArray();
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
        $entity = $this->createEntity();
        $idProperty->setValue($entity, 1);

        $entity->setDeliveryId($deliveryId);
        $entity->setDeliveryName($deliveryName);

        // 测试__toString方法
        $this->assertStringContainsString($deliveryName, (string) $entity);
    }
}
