<?php

namespace WechatMiniProgramExpressBundle\Tests\Request;

use HttpClientBundle\Tests\Request\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Request\AbnormalConfirmRequest;

/**
 * @internal
 */
#[CoversClass(AbnormalConfirmRequest::class)]
final class AbnormalConfirmRequestTest extends RequestTestCase
{
    private AbnormalConfirmRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new AbnormalConfirmRequest();
    }

    public function testRequestCanBeInstantiated(): void
    {
        $this->assertInstanceOf(AbnormalConfirmRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $this->assertSame('/cgi-bin/express/local/business/order/confirm_return', $this->request->getRequestPath());
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
        $deliverySign = 'sign123';
        $shopNo = 'shopno456';
        $remark = '确认收货';

        $this->request->setShopId($shopId);
        $this->request->setShopOrderId($shopOrderId);
        $this->request->setWaybillId($waybillId);
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
        $this->assertSame($deliverySign, $jsonData['delivery_sign']);
        $this->assertSame($shopNo, $jsonData['shop_no']);
        $this->assertSame($remark, $jsonData['remark']);
    }

    public function testFluentInterface(): void
    {
        $this->request->setShopId('shop123');
        $this->request->setShopOrderId('order456');
        $this->request->setWaybillId('waybill789');
        $this->request->setDeliverySign('sign123');
        $this->request->setShopNo('shopno456');
        $this->request->setRemark('确认收货');

        $this->assertSame($this->request, $this->request);
    }
}
