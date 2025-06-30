<?php

namespace WechatMiniProgramExpressBundle\Tests\Unit\Request;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Request\MockUpdateOrderRequest;

class MockUpdateOrderRequestTest extends TestCase
{
    private MockUpdateOrderRequest $request;

    protected function setUp(): void
    {
        $this->request = new MockUpdateOrderRequest();
    }

    public function testRequestCanBeInstantiated(): void
    {
        $this->assertInstanceOf(MockUpdateOrderRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $this->assertSame('/cgi-bin/express/local/business/order/mock_update_order', $this->request->getRequestPath());
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
        $actionType = 'OnAccept';
        $mockInfo = 'test mock info';

        $this->request->setOrderId($orderId);
        $this->request->setDeliveryId($deliveryId);
        $this->request->setShopId($shopId);
        $this->request->setActionType($actionType);
        $this->request->setMockInfo($mockInfo);

        $options = $this->request->getRequestOptions();

        $this->assertArrayHasKey('json', $options);
        $this->assertSame($orderId, $options['json']['order_id']);
        $this->assertSame($deliveryId, $options['json']['delivery_id']);
        $this->assertSame($shopId, $options['json']['shop_id']);
        $this->assertSame($actionType, $options['json']['action_type']);
        $this->assertSame($mockInfo, $options['json']['mock_info']);
    }

    public function testFluentInterface(): void
    {
        $result = $this->request->setOrderId('test')
            ->setDeliveryId('test')
            ->setShopId('test')
            ->setActionType('OnAccept')
            ->setMockInfo(null);

        $this->assertSame($this->request, $result);
    }
}