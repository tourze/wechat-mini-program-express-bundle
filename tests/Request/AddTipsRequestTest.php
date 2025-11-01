<?php

namespace WechatMiniProgramExpressBundle\Tests\Request;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatMiniProgramExpressBundle\Request\AddTipsRequest;

/**
 * @internal
 */
#[CoversClass(AddTipsRequest::class)]
final class AddTipsRequestTest extends RequestTestCase
{
    private AddTipsRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        // 使用容器获取服务实例，这符合集成测试最佳实践
        $this->request = new AddTipsRequest();
    }

    public function testRequestCanBeInstantiated(): void
    {
        $this->assertInstanceOf(AddTipsRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $this->assertSame('/cgi-bin/express/local/business/order/addtips', $this->request->getRequestPath());
    }

    public function testGetRequestMethod(): void
    {
        $this->assertSame('POST', $this->request->getRequestMethod());
    }

    public function testSettersAndGetRequestOptions(): void
    {
        $shopId = 'shop123';
        $shopOrderId = 'order456';
        $waybillId = 'waybill789';
        $tips = 5.5;
        $deliverySign = 'sign123';
        $shopNo = 'shopno456';
        $remark = '感谢配送';

        $this->request->setShopId($shopId);
        $this->request->setShopOrderId($shopOrderId);
        $this->request->setWaybillId($waybillId);
        $this->request->setTips($tips);
        $this->request->setDeliverySign($deliverySign);
        $this->request->setShopNo($shopNo);
        $this->request->setRemark($remark);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $jsonData = $options['json'];
        $this->assertIsArray($jsonData);

        $this->assertSame($shopId, $jsonData['shopid']);
        $this->assertSame($shopOrderId, $jsonData['shop_order_id']);
        $this->assertSame($waybillId, $jsonData['waybill_id']);
        $this->assertSame($tips, $jsonData['tips']);
        $this->assertSame($deliverySign, $jsonData['delivery_sign']);
        $this->assertSame($shopNo, $jsonData['shop_no']);
        $this->assertSame($remark, $jsonData['remark']);
    }

    public function testFluentInterface(): void
    {
        $this->request->setShopId('shop123');
        $this->request->setShopOrderId('order456');
        $this->request->setWaybillId('waybill789');
        $this->request->setTips(5.5);
        $this->request->setDeliverySign('sign123');
        $this->request->setShopNo('shopno456');
        $this->request->setRemark('感谢配送');

        $this->assertSame($this->request, $this->request);
    }
}
