<?php

namespace WechatMiniProgramExpressBundle\Tests\Unit\Request;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Request\RealMockUpdateOrderRequest;

class RealMockUpdateOrderRequestTest extends TestCase
{
    private RealMockUpdateOrderRequest $request;

    protected function setUp(): void
    {
        $this->request = new RealMockUpdateOrderRequest();
    }

    public function testRequestCanBeInstantiated(): void
    {
        $this->assertInstanceOf(RealMockUpdateOrderRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $this->assertSame('/cgi-bin/express/local/business/realmock_update_order', $this->request->getRequestPath());
    }

    public function testGetRequestMethod(): void
    {
        $this->assertSame('POST', $this->request->getRequestMethod());
    }

    public function testSettersAndGetRequestOptions(): void
    {
        $shopId = 'shop123';
        $shopOrderId = 'order456';
        $orderStatus = 100;
        $actionTime = 1234567890;
        $actionMsg = '配送员已接单';
        $deliverySign = 'sign123';

        $this->request->setShopId($shopId);
        $this->request->setShopOrderId($shopOrderId);
        $this->request->setOrderStatus($orderStatus);
        $this->request->setActionTime($actionTime);
        $this->request->setActionMsg($actionMsg);
        $this->request->setDeliverySign($deliverySign);

        $options = $this->request->getRequestOptions();

        $this->assertArrayHasKey('json', $options);
        $this->assertSame($shopId, $options['json']['shopid']);
        $this->assertSame($shopOrderId, $options['json']['shop_order_id']);
        $this->assertSame($orderStatus, $options['json']['order_status']);
        $this->assertSame($actionTime, $options['json']['action_time']);
        $this->assertSame($actionMsg, $options['json']['action_msg']);
        $this->assertSame($deliverySign, $options['json']['delivery_sign']);
    }

    public function testFluentInterface(): void
    {
        $result = $this->request->setShopId('shop123')
            ->setShopOrderId('order456')
            ->setOrderStatus(100)
            ->setActionTime(1234567890)
            ->setActionMsg('测试消息')
            ->setDeliverySign('sign123');

        $this->assertSame($this->request, $result);
    }

    public function testGetRequestOptionsWithDefaultValues(): void
    {
        $options = $this->request->getRequestOptions();

        $this->assertArrayHasKey('json', $options);
        $this->assertSame('', $options['json']['shopid']);
        $this->assertSame('', $options['json']['shop_order_id']);
        $this->assertSame(0, $options['json']['order_status']);
        $this->assertSame(0, $options['json']['action_time']);
        $this->assertNull($options['json']['action_msg']);
        $this->assertSame('', $options['json']['delivery_sign']);
    }
}