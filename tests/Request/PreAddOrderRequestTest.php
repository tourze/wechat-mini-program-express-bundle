<?php

namespace WechatMiniProgramExpressBundle\Tests\Request;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatMiniProgramExpressBundle\Request\PreAddOrderRequest;

/**
 * @internal
 */
#[CoversClass(PreAddOrderRequest::class)]
final class PreAddOrderRequestTest extends RequestTestCase
{
    private PreAddOrderRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        // 使用容器获取服务实例，这符合集成测试最佳实践
        $this->request = new PreAddOrderRequest();
    }

    public function testRequestCanBeInstantiated(): void
    {
        $this->assertInstanceOf(PreAddOrderRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $this->assertSame('/cgi-bin/express/local/business/order/pre_add', $this->request->getRequestPath());
    }

    public function testGetRequestMethod(): void
    {
        $this->assertSame('POST', $this->request->getRequestMethod());
    }

    public function testBasicSetters(): void
    {
        $shopId = 'shop123';
        $shopNo = 'shopno456';
        $deliveryId = 'delivery789';
        $shopOrderId = 'order123';

        $this->request->setShopId($shopId);
        $this->request->setShopNo($shopNo);
        $this->request->setDeliveryId($deliveryId);
        $this->request->setShopOrderId($shopOrderId);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $jsonData = $options['json'];
        $this->assertIsArray($jsonData);

        $this->assertSame($shopId, $jsonData['shopid']);
        $this->assertSame($shopNo, $jsonData['shop_no']);
        $this->assertSame($deliveryId, $jsonData['delivery_id']);
        $this->assertSame($shopOrderId, $jsonData['shop_order_id']);
    }

    public function testSetCargo(): void
    {
        $cargo = [
            'goods_value' => 100.0,
            'goods_height' => 10.0,
        ];

        $this->request->setCargo($cargo);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $jsonData = $options['json'];
        $this->assertIsArray($jsonData);

        $this->assertArrayHasKey('cargo', $jsonData);
        $cargoData = $jsonData['cargo'];
        $this->assertIsArray($cargoData);
        $this->assertSame(100.0, $cargoData['goods_value']);
        $this->assertSame(10.0, $cargoData['goods_height']);
    }

    public function testSetReceiver(): void
    {
        $receiver = [
            'name' => '张三',
            'mobile' => '13800138000',
        ];

        $this->request->setReceiver($receiver);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $jsonData = $options['json'];
        $this->assertIsArray($jsonData);

        $this->assertArrayHasKey('receiver', $jsonData);
        $receiverData = $jsonData['receiver'];
        $this->assertIsArray($receiverData);
        $this->assertSame('张三', $receiverData['name']);
        $this->assertSame('13800138000', $receiverData['mobile']);
    }

    public function testSetSender(): void
    {
        $sender = [
            'name' => '李四',
            'mobile' => '13800138001',
        ];

        $this->request->setSender($sender);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $jsonData = $options['json'];
        $this->assertIsArray($jsonData);

        $this->assertArrayHasKey('sender', $jsonData);
        $senderData = $jsonData['sender'];
        $this->assertIsArray($senderData);
        $this->assertSame('李四', $senderData['name']);
        $this->assertSame('13800138001', $senderData['mobile']);
    }

    public function testSetOrderInfo(): void
    {
        $orderInfo = [
            'order_type' => 1,
            'poi_seq' => 'POI123',
        ];

        $this->request->setOrderInfo($orderInfo);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $jsonData = $options['json'];
        $this->assertIsArray($jsonData);

        $this->assertArrayHasKey('order_info', $jsonData);
        $orderInfoData = $jsonData['order_info'];
        $this->assertIsArray($orderInfoData);
        $this->assertSame(1, $orderInfoData['order_type']);
        $this->assertSame('POI123', $orderInfoData['poi_seq']);
    }

    public function testSetShop(): void
    {
        $shop = [
            'goods_name' => '测试商品',
            'goods_count' => 2,
        ];

        $this->request->setShop($shop);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $jsonData = $options['json'];
        $this->assertIsArray($jsonData);

        $this->assertArrayHasKey('shop', $jsonData);
        $shopData = $jsonData['shop'];
        $this->assertIsArray($shopData);
        $this->assertSame('测试商品', $shopData['goods_name']);
        $this->assertSame(2, $shopData['goods_count']);
    }

    public function testFluentInterface(): void
    {
        $this->request->setShopId('shop123');
        $this->request->setShopNo('shopno456');
        $this->request->setDeliveryId('delivery789');
        $this->request->setShopOrderId('order123');

        $this->assertSame($this->request, $this->request);
    }

    public function testGetRequestOptionsWithEmptyValues(): void
    {
        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $jsonData = $options['json'];
        $this->assertIsArray($jsonData);

        $this->assertSame('', $jsonData['shopid']);
        $this->assertArrayNotHasKey('shop_no', $jsonData); // shop_no为null时不会被包含
        $this->assertSame('', $jsonData['delivery_id']);
        $this->assertSame('', $jsonData['shop_order_id']);
        $this->assertSame([], $jsonData['cargo']);
        $this->assertSame([], $jsonData['receiver']);
        $this->assertSame([], $jsonData['sender']);
        $this->assertSame([], $jsonData['order_info']);
        $this->assertArrayNotHasKey('shop', $jsonData); // shop为null时不会被包含
    }
}
