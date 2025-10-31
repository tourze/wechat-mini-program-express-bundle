<?php

namespace WechatMiniProgramExpressBundle\Tests\Request;

use HttpClientBundle\Tests\Request\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Request\CancelOrderRequest;

/**
 * @internal
 */
#[CoversClass(CancelOrderRequest::class)]
final class CancelOrderRequestTest extends RequestTestCase
{
    private CancelOrderRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

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

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $jsonData = $options['json'];
        $this->assertIsArray($jsonData);

        $this->assertSame($orderId, $jsonData['order_id']);
        $this->assertSame($deliveryId, $jsonData['delivery_id']);
        $this->assertSame($shopId, $jsonData['shop_id']);
        $this->assertSame($cancelReasonId, $jsonData['cancel_reason_id']);
        $this->assertSame($cancelReason, $jsonData['cancel_reason']);
    }

    public function testFluentInterface(): void
    {
        $this->request->setOrderId('order123');
        $this->request->setDeliveryId('delivery456');
        $this->request->setShopId('shop789');
        $this->request->setCancelReasonId(10001);
        $this->request->setCancelReason('商家主动取消');

        $this->assertSame($this->request, $this->request);
    }
}
