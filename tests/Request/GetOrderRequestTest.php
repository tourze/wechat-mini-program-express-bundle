<?php

namespace WechatMiniProgramExpressBundle\Tests\Request;

use HttpClientBundle\Tests\Request\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Request\GetOrderRequest;

/**
 * @internal
 */
#[CoversClass(GetOrderRequest::class)]
final class GetOrderRequestTest extends RequestTestCase
{
    private GetOrderRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        // 使用容器获取服务实例，这符合集成测试最佳实践
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

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $jsonData = $options['json'];
        $this->assertIsArray($jsonData);

        $this->assertArrayHasKey('order_id', $jsonData);
        $this->assertArrayHasKey('delivery_id', $jsonData);
        $this->assertArrayHasKey('shop_id', $jsonData);

        $this->assertSame($orderId, $jsonData['order_id']);
        $this->assertSame($deliveryId, $jsonData['delivery_id']);
        $this->assertSame($shopId, $jsonData['shop_id']);
    }

    public function testSetOrderId(): void
    {
        $orderId = 'order123';
        $this->request->setOrderId($orderId);

        $this->assertSame($this->request, $this->request);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $jsonData = $options['json'];
        $this->assertIsArray($jsonData);

        $this->assertSame($orderId, $jsonData['order_id']);
    }

    public function testSetDeliveryId(): void
    {
        $deliveryId = 'delivery456';
        $this->request->setDeliveryId($deliveryId);

        $this->assertSame($this->request, $this->request);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $jsonData = $options['json'];
        $this->assertIsArray($jsonData);

        $this->assertSame($deliveryId, $jsonData['delivery_id']);
    }

    public function testSetShopId(): void
    {
        $shopId = 'shop789';
        $this->request->setShopId($shopId);

        $this->assertSame($this->request, $this->request);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $jsonData = $options['json'];
        $this->assertIsArray($jsonData);

        $this->assertSame($shopId, $jsonData['shop_id']);
    }

    public function testGetRequestOptionsWithDefaultValues(): void
    {
        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $jsonData = $options['json'];
        $this->assertIsArray($jsonData);

        $this->assertArrayHasKey('order_id', $jsonData);
        $this->assertArrayHasKey('delivery_id', $jsonData);
        $this->assertArrayHasKey('shop_id', $jsonData);

        $this->assertSame('', $jsonData['order_id']);
        $this->assertSame('', $jsonData['delivery_id']);
        $this->assertSame('', $jsonData['shop_id']);
    }
}
