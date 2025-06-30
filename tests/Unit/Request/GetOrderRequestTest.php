<?php

namespace WechatMiniProgramExpressBundle\Tests\Unit\Request;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Request\GetOrderRequest;

class GetOrderRequestTest extends TestCase
{
    private GetOrderRequest $request;

    protected function setUp(): void
    {
        $this->request = new GetOrderRequest();
    }

    public function testRequestCanBeInstantiated(): void
    {
        $this->assertInstanceOf(GetOrderRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $this->assertSame('/cgi-bin/express/local/business/order/get', $this->request->getRequestPath());
    }

    public function testGetRequestMethod(): void
    {
        $this->assertSame('POST', $this->request->getRequestMethod());
    }

    public function testSettersAndGetRequestOptions(): void
    {
        $orderId = 'order123';
        $deliveryId = 'delivery456';
        $shopId = 'shop789';

        $this->request->setOrderId($orderId);
        $this->request->setDeliveryId($deliveryId);
        $this->request->setShopId($shopId);

        $options = $this->request->getRequestOptions();

        $this->assertArrayHasKey('json', $options);
        $this->assertArrayHasKey('order_id', $options['json']);
        $this->assertArrayHasKey('delivery_id', $options['json']);
        $this->assertArrayHasKey('shop_id', $options['json']);

        $this->assertSame($orderId, $options['json']['order_id']);
        $this->assertSame($deliveryId, $options['json']['delivery_id']);
        $this->assertSame($shopId, $options['json']['shop_id']);
    }

    public function testSetOrderId(): void
    {
        $orderId = 'order123';
        $result = $this->request->setOrderId($orderId);

        $this->assertSame($this->request, $result);
        
        $options = $this->request->getRequestOptions();
        $this->assertSame($orderId, $options['json']['order_id']);
    }

    public function testSetDeliveryId(): void
    {
        $deliveryId = 'delivery456';
        $result = $this->request->setDeliveryId($deliveryId);

        $this->assertSame($this->request, $result);
        
        $options = $this->request->getRequestOptions();
        $this->assertSame($deliveryId, $options['json']['delivery_id']);
    }

    public function testSetShopId(): void
    {
        $shopId = 'shop789';
        $result = $this->request->setShopId($shopId);

        $this->assertSame($this->request, $result);
        
        $options = $this->request->getRequestOptions();
        $this->assertSame($shopId, $options['json']['shop_id']);
    }

    public function testGetRequestOptionsWithDefaultValues(): void
    {
        $options = $this->request->getRequestOptions();

        $this->assertArrayHasKey('json', $options);
        $this->assertArrayHasKey('order_id', $options['json']);
        $this->assertArrayHasKey('delivery_id', $options['json']);
        $this->assertArrayHasKey('shop_id', $options['json']);

        $this->assertSame('', $options['json']['order_id']);
        $this->assertSame('', $options['json']['delivery_id']);
        $this->assertSame('', $options['json']['shop_id']);
    }
}