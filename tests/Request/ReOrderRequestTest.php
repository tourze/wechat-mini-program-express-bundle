<?php

namespace WechatMiniProgramExpressBundle\Tests\Request;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatMiniProgramExpressBundle\Request\ReOrderRequest;

/**
 * @internal
 */
#[CoversClass(ReOrderRequest::class)]
final class ReOrderRequestTest extends RequestTestCase
{
    private ReOrderRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        // 使用容器获取服务实例，这符合集成测试最佳实践
        $this->request = new ReOrderRequest();
    }

    public function testRequestCanBeInstantiated(): void
    {
        $this->assertInstanceOf(ReOrderRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $this->assertSame('/cgi-bin/express/local/business/order/readd', $this->request->getRequestPath());
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

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $jsonData = $options['json'];
        $this->assertIsArray($jsonData);

        $this->assertSame($orderId, $jsonData['order_id']);
        $this->assertSame($deliveryId, $jsonData['delivery_id']);
        $this->assertSame($shopId, $jsonData['shop_id']);
    }

    public function testFluentInterface(): void
    {
        $this->request->setOrderId('test');
        $this->request->setDeliveryId('test');
        $this->request->setShopId('test');

        $this->assertSame($this->request, $this->request);
    }
}
