<?php

namespace WechatMiniProgramExpressBundle\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Entity\Embed\CargoInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\OrderInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\ReceiverInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\SenderInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\ShopInfo;
use WechatMiniProgramExpressBundle\Entity\Order;

class OrderTest extends TestCase
{
    private Order $order;

    protected function setUp(): void
    {
        $this->order = new Order();
    }

    public function testConstructorInitializesEmbeddedObjects(): void
    {
        $this->assertInstanceOf(SenderInfo::class, $this->order->getSenderInfo());
        $this->assertInstanceOf(ReceiverInfo::class, $this->order->getReceiverInfo());
        $this->assertInstanceOf(CargoInfo::class, $this->order->getCargoInfo());
        $this->assertInstanceOf(OrderInfo::class, $this->order->getOrderInfo());
        $this->assertInstanceOf(ShopInfo::class, $this->order->getShopInfo());
    }

    public function testGettersAndSetters(): void
    {
        $wechatOrderId = 'wx123456';
        $deliveryId = 'del123456';
        $status = 'pending';
        $fee = '12.5';
        $deliveryCompanyId = 'company123';
        $bindAccountId = 'account123';
        $requestData = ['key' => 'value'];
        $responseData = ['response' => 'data'];

        $this->order->setWechatOrderId($wechatOrderId);
        $this->order->setDeliveryId($deliveryId);
        $this->order->setStatus($status);
        $this->order->setFee($fee);
        $this->order->setDeliveryCompanyId($deliveryCompanyId);
        $this->order->setBindAccountId($bindAccountId);
        $this->order->setRequestData($requestData);
        $this->order->setResponseData($responseData);

        $this->assertSame($wechatOrderId, $this->order->getWechatOrderId());
        $this->assertSame($deliveryId, $this->order->getDeliveryId());
        $this->assertSame($status, $this->order->getStatus());
        $this->assertSame($fee, $this->order->getFee());
        $this->assertSame($deliveryCompanyId, $this->order->getDeliveryCompanyId());
        $this->assertSame($bindAccountId, $this->order->getBindAccountId());
        $this->assertSame($requestData, $this->order->getRequestData());
        $this->assertSame($responseData, $this->order->getResponseData());
    }

    public function testEmbeddedObjectSetters(): void
    {
        $senderInfo = new SenderInfo();
        $receiverInfo = new ReceiverInfo();
        $cargoInfo = new CargoInfo();
        $orderInfo = new OrderInfo();
        $shopInfo = new ShopInfo();

        $this->order->setSenderInfo($senderInfo);
        $this->order->setReceiverInfo($receiverInfo);
        $this->order->setCargoInfo($cargoInfo);
        $this->order->setOrderInfo($orderInfo);
        $this->order->setShopInfo($shopInfo);

        $this->assertSame($senderInfo, $this->order->getSenderInfo());
        $this->assertSame($receiverInfo, $this->order->getReceiverInfo());
        $this->assertSame($cargoInfo, $this->order->getCargoInfo());
        $this->assertSame($orderInfo, $this->order->getOrderInfo());
        $this->assertSame($shopInfo, $this->order->getShopInfo());
    }

    public function testToRequestArray(): void
    {
        $this->order->setDeliveryCompanyId('company123');
        $this->order->setBindAccountId('account123');
        
        // 设置OrderInfo的poiSeq
        $this->order->getOrderInfo()->setPoiSeq('POI12345');
        
        // 设置一些基本数据以确保embedded对象不会被过滤掉
        $this->order->getSenderInfo()->setName('测试发送方');
        $this->order->getReceiverInfo()->setName('测试接收方');
        $this->order->getCargoInfo()->setGoodsValue(100.0);

        $requestArray = $this->order->toRequestArray();

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
        $this->order->setDeliveryCompanyId('company123');
        $this->order->setBindAccountId('account123');
        $this->order->getOrderInfo()->setPoiSeq('POI12345');
        $this->order->getShopInfo()->setGoodsName('测试商品');

        $requestArray = $this->order->toRequestArray();

        $this->assertArrayHasKey('shop', $requestArray);
        $this->assertArrayHasKey('goods_name', $requestArray['shop']);
    }

    public function testToRequestArrayFiltersNullValues(): void
    {
        // 只设置必需的字段
        $this->order->setDeliveryCompanyId('company123');
        $this->order->setBindAccountId('account123');
        $this->order->getOrderInfo()->setPoiSeq('POI12345');

        $requestArray = $this->order->toRequestArray();

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

        $this->order->updateFromResponse($response);

        $this->assertSame('15.5', $this->order->getFee());
        $this->assertSame('wx789', $this->order->getWechatOrderId());
        $this->assertSame('del789', $this->order->getDeliveryId());
        $this->assertSame('confirmed', $this->order->getStatus());
    }

    public function testUpdateFromResponseWithPartialData(): void
    {
        $response = [
            'fee' => 20.0,
        ];

        $originalWechatOrderId = $this->order->getWechatOrderId();
        $originalDeliveryId = $this->order->getDeliveryId();
        $originalStatus = $this->order->getStatus();

        $this->order->updateFromResponse($response);

        $this->assertSame('20', $this->order->getFee());
        $this->assertSame($originalWechatOrderId, $this->order->getWechatOrderId());
        $this->assertSame($originalDeliveryId, $this->order->getDeliveryId());
        $this->assertSame($originalStatus, $this->order->getStatus());
    }

    public function testUpdateFromResponseWithEmptyData(): void
    {
        $originalFee = $this->order->getFee();
        $originalWechatOrderId = $this->order->getWechatOrderId();

        $this->order->updateFromResponse([]);

        $this->assertSame($originalFee, $this->order->getFee());
        $this->assertSame($originalWechatOrderId, $this->order->getWechatOrderId());
    }

    public function testToString(): void
    {
        // 通过反射设置ID
        $reflectionClass = new \ReflectionClass(Order::class);
        $idProperty = $reflectionClass->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($this->order, 123);

        $this->assertSame('123', (string) $this->order);
    }

    public function testToStringWithNullId(): void
    {
        $this->assertSame('', (string) $this->order);
    }

    public function testGetId(): void
    {
        $this->assertNull($this->order->getId());
    }
}