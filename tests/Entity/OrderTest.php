<?php

namespace WechatMiniProgramExpressBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatMiniProgramExpressBundle\Entity\Embed\CargoInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\OrderInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\ReceiverInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\SenderInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\ShopInfo;
use WechatMiniProgramExpressBundle\Entity\Order;

/**
 * @internal
 */
#[CoversClass(Order::class)]
final class OrderTest extends AbstractEntityTestCase
{
    protected function createEntity(): Order
    {
        return new Order();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'senderInfo' => ['senderInfo', new SenderInfo()],
            'receiverInfo' => ['receiverInfo', new ReceiverInfo()],
            'cargoInfo' => ['cargoInfo', new CargoInfo()],
            'orderInfo' => ['orderInfo', new OrderInfo()],
            'shopInfo' => ['shopInfo', new ShopInfo()],
        ];
    }

    public function testConstructorInitializesEmbeddedObjects(): void
    {
        $entity = $this->createEntity();
        $this->assertInstanceOf(SenderInfo::class, $entity->getSenderInfo());
        $this->assertInstanceOf(ReceiverInfo::class, $entity->getReceiverInfo());
        $this->assertInstanceOf(CargoInfo::class, $entity->getCargoInfo());
        $this->assertInstanceOf(OrderInfo::class, $entity->getOrderInfo());
        $this->assertInstanceOf(ShopInfo::class, $entity->getShopInfo());
    }

    public function testEmbeddedObjectSetters(): void
    {
        $entity = $this->createEntity();
        $senderInfo = new SenderInfo();
        $receiverInfo = new ReceiverInfo();
        $cargoInfo = new CargoInfo();
        $orderInfo = new OrderInfo();
        $shopInfo = new ShopInfo();

        $entity->setSenderInfo($senderInfo);
        $entity->setReceiverInfo($receiverInfo);
        $entity->setCargoInfo($cargoInfo);
        $entity->setOrderInfo($orderInfo);
        $entity->setShopInfo($shopInfo);

        $this->assertSame($senderInfo, $entity->getSenderInfo());
        $this->assertSame($receiverInfo, $entity->getReceiverInfo());
        $this->assertSame($cargoInfo, $entity->getCargoInfo());
        $this->assertSame($orderInfo, $entity->getOrderInfo());
        $this->assertSame($shopInfo, $entity->getShopInfo());
    }

    public function testToRequestArray(): void
    {
        $entity = $this->createEntity();
        $entity->setDeliveryCompanyId('company123');
        $entity->setBindAccountId('account123');

        // 设置OrderInfo的poiSeq
        $entity->getOrderInfo()->setPoiSeq('POI12345');

        // 设置一些基本数据以确保embedded对象不会被过滤掉
        $entity->getSenderInfo()->setName('测试发送方');
        $entity->getReceiverInfo()->setName('测试接收方');
        $entity->getCargoInfo()->setGoodsValue(100.0);

        $requestArray = $entity->toRequestArray();

        $this->assertArrayHasKey('delivery_id', $requestArray);
        $this->assertArrayHasKey('shopid', $requestArray);
        $this->assertArrayHasKey('shop_order_id', $requestArray);
        $this->assertArrayHasKey('sender', $requestArray);
        $this->assertArrayHasKey('receiver', $requestArray);
        $this->assertArrayHasKey('cargo', $requestArray);
        $this->assertArrayHasKey('order_info', $requestArray);
        $this->assertArrayHasKey('shop_no_order', $requestArray);

        $this->assertSame('company123', $requestArray['delivery_id']);
        $this->assertSame('account123', $requestArray['shopid']);
        $this->assertSame('POI12345', $requestArray['shop_order_id']);
        $this->assertSame(0, $requestArray['shop_no_order']);
    }

    public function testToRequestArrayWithShopInfo(): void
    {
        $entity = $this->createEntity();
        $entity->setDeliveryCompanyId('company123');
        $entity->setBindAccountId('account123');
        $entity->getOrderInfo()->setPoiSeq('POI12345');
        $entity->getShopInfo()->setGoodsName('测试商品');

        $requestArray = $entity->toRequestArray();

        $this->assertArrayHasKey('shop', $requestArray);
        $this->assertIsArray($requestArray['shop']);
        $this->assertArrayHasKey('goods_name', $requestArray['shop']);
    }

    public function testToRequestArrayFiltersNullValues(): void
    {
        // 只设置必需的字段
        $entity = $this->createEntity();
        $entity->setDeliveryCompanyId('company123');
        $entity->setBindAccountId('account123');
        $entity->getOrderInfo()->setPoiSeq('POI12345');

        $requestArray = $entity->toRequestArray();

        // 验证数组不包含null值
        foreach ($requestArray as $key => $value) {
            $this->assertNotNull($value, "Key '{$key}' should not be null");
            if (is_array($value)) {
                $this->assertNotEmpty($value, "Array value for key '{$key}' should not be empty");
            }
        }
    }

    public function testUpdateFromResponse(): void
    {
        $response = [
            'fee' => 15.5,
            'order_id' => 'wx789',
            'delivery_id' => 'del789',
            'status' => 'confirmed',
        ];

        $entity = $this->createEntity();
        $entity->updateFromResponse($response);

        $this->assertSame('15.5', $entity->getFee());
        $this->assertSame('wx789', $entity->getWechatOrderId());
        $this->assertSame('del789', $entity->getDeliveryId());
        $this->assertSame('confirmed', $entity->getStatus());
    }

    public function testUpdateFromResponseWithPartialData(): void
    {
        $response = [
            'fee' => 20.0,
        ];

        $entity = $this->createEntity();
        $originalWechatOrderId = $entity->getWechatOrderId();
        $originalDeliveryId = $entity->getDeliveryId();
        $originalStatus = $entity->getStatus();

        $entity->updateFromResponse($response);

        $this->assertSame('20', $entity->getFee());
        $this->assertSame($originalWechatOrderId, $entity->getWechatOrderId());
        $this->assertSame($originalDeliveryId, $entity->getDeliveryId());
        $this->assertSame($originalStatus, $entity->getStatus());
    }

    public function testUpdateFromResponseWithEmptyData(): void
    {
        $entity = $this->createEntity();
        $originalFee = $entity->getFee();
        $originalWechatOrderId = $entity->getWechatOrderId();

        $entity->updateFromResponse([]);

        $this->assertSame($originalFee, $entity->getFee());
        $this->assertSame($originalWechatOrderId, $entity->getWechatOrderId());
    }

    public function testToString(): void
    {
        // 通过反射设置ID
        $entity = $this->createEntity();
        $reflectionClass = new \ReflectionClass(Order::class);
        $idProperty = $reflectionClass->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($entity, 123);

        $this->assertSame('123', (string) $entity);
    }

    public function testToStringWithNullId(): void
    {
        $this->assertSame('', (string) $this->createEntity());
    }

    public function testGetId(): void
    {
        $this->assertNull($this->createEntity()->getId());
    }
}
