<?php

namespace WechatMiniProgramExpressBundle\Tests\Unit\Request;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Request\AddTipsRequest;

class AddTipsRequestTest extends TestCase
{
    private AddTipsRequest $request;

    protected function setUp(): void
    {
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

        $this->assertArrayHasKey('json', $options);
        $this->assertSame($shopId, $options['json']['shopid']);
        $this->assertSame($shopOrderId, $options['json']['shop_order_id']);
        $this->assertSame($waybillId, $options['json']['waybill_id']);
        $this->assertSame($tips, $options['json']['tips']);
        $this->assertSame($deliverySign, $options['json']['delivery_sign']);
        $this->assertSame($shopNo, $options['json']['shop_no']);
        $this->assertSame($remark, $options['json']['remark']);
    }

    public function testFluentInterface(): void
    {
        $result = $this->request->setShopId('shop123')
            ->setShopOrderId('order456')
            ->setWaybillId('waybill789')
            ->setTips(5.5)
            ->setDeliverySign('sign123')
            ->setShopNo('shopno456')
            ->setRemark('感谢配送');

        $this->assertSame($this->request, $result);
    }
}