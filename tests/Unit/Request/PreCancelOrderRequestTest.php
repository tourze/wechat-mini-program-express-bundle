<?php

namespace WechatMiniProgramExpressBundle\Tests\Unit\Request;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Request\PreCancelOrderRequest;

class PreCancelOrderRequestTest extends TestCase
{
    private PreCancelOrderRequest $request;

    protected function setUp(): void
    {
        $this->request = new PreCancelOrderRequest();
    }

    public function testRequestCanBeInstantiated(): void
    {
        $this->assertInstanceOf(PreCancelOrderRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $this->assertSame('/cgi-bin/express/local/business/order/precancel', $this->request->getRequestPath());
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
        $this->assertSame($orderId, $options['json']['order_id']);
        $this->assertSame($deliveryId, $options['json']['delivery_id']);
        $this->assertSame($shopId, $options['json']['shop_id']);
    }

    public function testFluentInterface(): void
    {
        $result = $this->request->setOrderId('test')
            ->setDeliveryId('test')
            ->setShopId('test');

        $this->assertSame($this->request, $result);
    }
}