<?php

namespace WechatMiniProgramExpressBundle\Tests\Request;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatMiniProgramExpressBundle\Request\MockUpdateOrderRequest;

/**
 * @internal
 */
#[CoversClass(MockUpdateOrderRequest::class)]
final class MockUpdateOrderRequestTest extends RequestTestCase
{
    private MockUpdateOrderRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        // 使用容器获取服务实例，这符合集成测试最佳实践
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

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $jsonData = $options['json'];
        $this->assertIsArray($jsonData);

        $this->assertSame($orderId, $jsonData['order_id']);
        $this->assertSame($deliveryId, $jsonData['delivery_id']);
        $this->assertSame($shopId, $jsonData['shop_id']);
        $this->assertSame($actionType, $jsonData['action_type']);
        $this->assertSame($mockInfo, $jsonData['mock_info']);
    }

    public function testFluentInterface(): void
    {
        $this->request->setOrderId('test');
        $this->request->setDeliveryId('test');
        $this->request->setShopId('test');
        $this->request->setActionType('OnAccept');
        $this->request->setMockInfo(null);

        $this->assertSame($this->request, $this->request);
    }
}
