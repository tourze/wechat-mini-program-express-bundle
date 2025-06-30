<?php

namespace WechatMiniProgramExpressBundle\Tests\Unit\Request;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Request\CancelOrderRequest;

class CancelOrderRequestTest extends TestCase
{
    private CancelOrderRequest $request;

    protected function setUp(): void
    {
        $this->request = new CancelOrderRequest();
    }

    public function testRequestCanBeInstantiated(): void
    {
        $this->assertInstanceOf(CancelOrderRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $this->assertSame('/cgi-bin/express/local/business/order/cancel', $this->request->getRequestPath());
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
        $cancelReasonId = 10001;
        $cancelReason = '商家主动取消';

        $this->request->setOrderId($orderId);
        $this->request->setDeliveryId($deliveryId);
        $this->request->setShopId($shopId);
        $this->request->setCancelReasonId($cancelReasonId);
        $this->request->setCancelReason($cancelReason);

        $options = $this->request->getRequestOptions();

        $this->assertArrayHasKey('json', $options);
        $this->assertSame($orderId, $options['json']['order_id']);
        $this->assertSame($deliveryId, $options['json']['delivery_id']);
        $this->assertSame($shopId, $options['json']['shop_id']);
        $this->assertSame($cancelReasonId, $options['json']['cancel_reason_id']);
        $this->assertSame($cancelReason, $options['json']['cancel_reason']);
    }

    public function testFluentInterface(): void
    {
        $result = $this->request->setOrderId('order123')
            ->setDeliveryId('delivery456')
            ->setShopId('shop789')
            ->setCancelReasonId(10001)
            ->setCancelReason('商家主动取消');

        $this->assertSame($this->request, $result);
    }
}