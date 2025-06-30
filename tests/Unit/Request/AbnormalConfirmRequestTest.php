<?php

namespace WechatMiniProgramExpressBundle\Tests\Unit\Request;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Request\AbnormalConfirmRequest;

class AbnormalConfirmRequestTest extends TestCase
{
    private AbnormalConfirmRequest $request;

    protected function setUp(): void
    {
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

        $this->assertArrayHasKey('json', $options);
        $this->assertSame($shopId, $options['json']['shopid']);
        $this->assertSame($shopOrderId, $options['json']['shop_order_id']);
        $this->assertSame($waybillId, $options['json']['waybill_id']);
        $this->assertSame($deliverySign, $options['json']['delivery_sign']);
        $this->assertSame($shopNo, $options['json']['shop_no']);
        $this->assertSame($remark, $options['json']['remark']);
    }

    public function testFluentInterface(): void
    {
        $result = $this->request->setShopId('shop123')
            ->setShopOrderId('order456')
            ->setWaybillId('waybill789')
            ->setDeliverySign('sign123')
            ->setShopNo('shopno456')
            ->setRemark('确认收货');

        $this->assertSame($this->request, $result);
    }
}