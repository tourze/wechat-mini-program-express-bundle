<?php

namespace WechatMiniProgramExpressBundle\Tests\Unit\Request;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Request\PreAddOrderRequest;

class PreAddOrderRequestTest extends TestCase
{
    private PreAddOrderRequest $request;

    protected function setUp(): void
    {
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

        $this->assertSame($shopId, $options['json']['shopid']);
        $this->assertSame($shopNo, $options['json']['shop_no']);
        $this->assertSame($deliveryId, $options['json']['delivery_id']);
        $this->assertSame($shopOrderId, $options['json']['shop_order_id']);
    }

    public function testSetCargo(): void
    {
        $cargo = [
            'goods_value' => 100.0,
            'goods_height' => 10.0,
        ];

        $this->request->setCargo($cargo);

        $options = $this->request->getRequestOptions();
        $this->assertArrayHasKey('cargo', $options['json']);
        $this->assertSame(100.0, $options['json']['cargo']['goods_value']);
        $this->assertSame(10.0, $options['json']['cargo']['goods_height']);
    }

    public function testSetReceiver(): void
    {
        $receiver = [
            'name' => '张三',
            'mobile' => '13800138000',
        ];

        $this->request->setReceiver($receiver);

        $options = $this->request->getRequestOptions();
        $this->assertArrayHasKey('receiver', $options['json']);
        $this->assertSame('张三', $options['json']['receiver']['name']);
        $this->assertSame('13800138000', $options['json']['receiver']['mobile']);
    }

    public function testSetSender(): void
    {
        $sender = [
            'name' => '李四',
            'mobile' => '13800138001',
        ];

        $this->request->setSender($sender);

        $options = $this->request->getRequestOptions();
        $this->assertArrayHasKey('sender', $options['json']);
        $this->assertSame('李四', $options['json']['sender']['name']);
        $this->assertSame('13800138001', $options['json']['sender']['mobile']);
    }

    public function testSetOrderInfo(): void
    {
        $orderInfo = [
            'order_type' => 1,
            'poi_seq' => 'POI123',
        ];

        $this->request->setOrderInfo($orderInfo);

        $options = $this->request->getRequestOptions();
        $this->assertArrayHasKey('order_info', $options['json']);
        $this->assertSame(1, $options['json']['order_info']['order_type']);
        $this->assertSame('POI123', $options['json']['order_info']['poi_seq']);
    }

    public function testSetShop(): void
    {
        $shop = [
            'goods_name' => '测试商品',
            'goods_count' => 2,
        ];

        $this->request->setShop($shop);

        $options = $this->request->getRequestOptions();
        $this->assertArrayHasKey('shop', $options['json']);
        $this->assertSame('测试商品', $options['json']['shop']['goods_name']);
        $this->assertSame(2, $options['json']['shop']['goods_count']);
    }

    public function testFluentInterface(): void
    {
        $result = $this->request->setShopId('shop123')
            ->setShopNo('shopno456')
            ->setDeliveryId('delivery789')
            ->setShopOrderId('order123');

        $this->assertSame($this->request, $result);
    }

    public function testGetRequestOptionsWithEmptyValues(): void
    {
        $options = $this->request->getRequestOptions();

        $this->assertArrayHasKey('json', $options);
        $this->assertSame('', $options['json']['shopid']);
        $this->assertArrayNotHasKey('shop_no', $options['json']); // shop_no为null时不会被包含
        $this->assertSame('', $options['json']['delivery_id']);
        $this->assertSame('', $options['json']['shop_order_id']);
        $this->assertSame([], $options['json']['cargo']);
        $this->assertSame([], $options['json']['receiver']);
        $this->assertSame([], $options['json']['sender']);
        $this->assertSame([], $options['json']['order_info']);
        $this->assertArrayNotHasKey('shop', $options['json']); // shop为null时不会被包含
    }
}