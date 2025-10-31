<?php

namespace WechatMiniProgramExpressBundle\Tests\Request;

use HttpClientBundle\Tests\Request\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Request\RealMockUpdateOrderRequest;

/**
 * @internal
 */
#[CoversClass(RealMockUpdateOrderRequest::class)]
final class RealMockUpdateOrderRequestTest extends RequestTestCase
{
    private RealMockUpdateOrderRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

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

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $jsonData = $options['json'];
        $this->assertIsArray($jsonData);

        $this->assertSame($shopId, $jsonData['shopid']);
        $this->assertSame($shopOrderId, $jsonData['shop_order_id']);
        $this->assertSame($orderStatus, $jsonData['order_status']);
        $this->assertSame($actionTime, $jsonData['action_time']);
        $this->assertSame($actionMsg, $jsonData['action_msg']);
        $this->assertSame($deliverySign, $jsonData['delivery_sign']);
    }

    public function testFluentInterface(): void
    {
        $this->request->setShopId('shop123');
        $this->request->setShopOrderId('order456');
        $this->request->setOrderStatus(100);
        $this->request->setActionTime(1234567890);
        $this->request->setActionMsg('测试消息');
        $this->request->setDeliverySign('sign123');

        $this->assertSame($this->request, $this->request);
    }

    public function testGetRequestOptionsWithDefaultValues(): void
    {
        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $jsonData = $options['json'];
        $this->assertIsArray($jsonData);

        $this->assertSame('', $jsonData['shopid']);
        $this->assertSame('', $jsonData['shop_order_id']);
        $this->assertSame(0, $jsonData['order_status']);
        $this->assertSame(0, $jsonData['action_time']);
        $this->assertArrayNotHasKey('action_msg', $jsonData);
        $this->assertSame('', $jsonData['delivery_sign']);
    }
}
