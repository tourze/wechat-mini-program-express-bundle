<?php

namespace WechatMiniProgramExpressBundle\Tests\Unit\Entity\Embed;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Entity\Embed\OrderInfo;

class OrderInfoTest extends TestCase
{
    private OrderInfo $orderInfo;

    protected function setUp(): void
    {
        $this->orderInfo = new OrderInfo();
    }

    public function testGettersAndSetters(): void
    {
        $orderTime = 1234567890;
        $orderType = 1;
        $poiSeq = 'POI123';
        $note = '测试备注';
        $isDirectDelivery = true;
        $isPickupCodeNeeded = true;
        $isFinishCodeNeeded = false;
        $expectedDeliveryTime = 1234567999;
        $deliveryServiceCode = 'CODE123';
        $isInsured = true;
        $tips = 5.5;

        $this->orderInfo->setOrderTime($orderTime);
        $this->orderInfo->setOrderType($orderType);
        $this->orderInfo->setPoiSeq($poiSeq);
        $this->orderInfo->setNote($note);
        $this->orderInfo->setIsDirectDelivery($isDirectDelivery);
        $this->orderInfo->setIsPickupCodeNeeded($isPickupCodeNeeded);
        $this->orderInfo->setIsFinishCodeNeeded($isFinishCodeNeeded);
        $this->orderInfo->setExpectedDeliveryTime($expectedDeliveryTime);
        $this->orderInfo->setDeliveryServiceCode($deliveryServiceCode);
        $this->orderInfo->setIsInsured($isInsured);
        $this->orderInfo->setTips($tips);

        $this->assertSame($orderTime, $this->orderInfo->getOrderTime());
        $this->assertSame($orderType, $this->orderInfo->getOrderType());
        $this->assertSame($poiSeq, $this->orderInfo->getPoiSeq());
        $this->assertSame($note, $this->orderInfo->getNote());
        $this->assertSame($isDirectDelivery, $this->orderInfo->getIsDirectDelivery());
        $this->assertSame($isPickupCodeNeeded, $this->orderInfo->getIsPickupCodeNeeded());
        $this->assertSame($isFinishCodeNeeded, $this->orderInfo->getIsFinishCodeNeeded());
        $this->assertSame($expectedDeliveryTime, $this->orderInfo->getExpectedDeliveryTime());
        $this->assertSame($deliveryServiceCode, $this->orderInfo->getDeliveryServiceCode());
        $this->assertSame($isInsured, $this->orderInfo->getIsInsured());
        $this->assertSame($tips, $this->orderInfo->getTips());
    }

    public function testToRequestArray(): void
    {
        $this->orderInfo->setOrderTime(1234567890);
        $this->orderInfo->setOrderType(1);
        $this->orderInfo->setPoiSeq('POI123');
        $this->orderInfo->setNote('测试备注');
        $this->orderInfo->setIsDirectDelivery(true);
        $this->orderInfo->setIsPickupCodeNeeded(false);
        $this->orderInfo->setIsFinishCodeNeeded(true);
        $this->orderInfo->setExpectedDeliveryTime(1234567999);
        $this->orderInfo->setDeliveryServiceCode('CODE123');
        $this->orderInfo->setIsInsured(false);
        $this->orderInfo->setTips(5.5);

        $array = $this->orderInfo->toRequestArray();

        $this->assertSame(1234567890, $array['order_time']);
        $this->assertSame(1, $array['order_type']);
        $this->assertSame('POI123', $array['poi_seq']);
        $this->assertSame('测试备注', $array['note']);
        $this->assertSame(1, $array['is_direct_delivery']);
        $this->assertSame(0, $array['is_pickup_code_needed']);
        $this->assertSame(1, $array['is_finish_code_needed']);
        $this->assertSame(1234567999, $array['expected_delivery_time']);
        $this->assertSame('CODE123', $array['delivery_service_code']);
        $this->assertSame(0, $array['is_insured']);
        $this->assertSame(5.5, $array['tips']);
    }

    public function testToRequestArrayWithNullBooleans(): void
    {
        $this->orderInfo->setOrderTime(1234567890);
        $this->orderInfo->setOrderType(1);
        // 布尔值保持null

        $array = $this->orderInfo->toRequestArray();

        $this->assertArrayHasKey('order_time', $array);
        $this->assertArrayHasKey('order_type', $array);
        $this->assertArrayNotHasKey('is_direct_delivery', $array);
        $this->assertArrayNotHasKey('is_pickup_code_needed', $array);
        $this->assertArrayNotHasKey('is_finish_code_needed', $array);
        $this->assertArrayNotHasKey('is_insured', $array);
    }

    public function testFromArray(): void
    {
        $data = [
            'order_time' => 1234567890,
            'order_type' => 1,
            'poi_seq' => 'POI123',
            'note' => '测试备注',
            'is_direct_delivery' => true,
            'is_pickup_code_needed' => false,
            'is_finish_code_needed' => true,
            'expected_delivery_time' => 1234567999,
            'delivery_service_code' => 'CODE123',
            'is_insured' => false,
            'tips' => 5.5,
        ];

        $orderInfo = OrderInfo::fromArray($data);

        $this->assertSame(1234567890, $orderInfo->getOrderTime());
        $this->assertSame(1, $orderInfo->getOrderType());
        $this->assertSame('POI123', $orderInfo->getPoiSeq());
        $this->assertSame('测试备注', $orderInfo->getNote());
        $this->assertTrue($orderInfo->getIsDirectDelivery());
        $this->assertFalse($orderInfo->getIsPickupCodeNeeded());
        $this->assertTrue($orderInfo->getIsFinishCodeNeeded());
        $this->assertSame(1234567999, $orderInfo->getExpectedDeliveryTime());
        $this->assertSame('CODE123', $orderInfo->getDeliveryServiceCode());
        $this->assertFalse($orderInfo->getIsInsured());
        $this->assertSame(5.5, $orderInfo->getTips());
    }

    public function testFromArrayWithPartialData(): void
    {
        $data = [
            'order_time' => 1234567890,
            'order_type' => 1,
        ];

        $orderInfo = OrderInfo::fromArray($data);

        $this->assertSame(1234567890, $orderInfo->getOrderTime());
        $this->assertSame(1, $orderInfo->getOrderType());
        $this->assertNull($orderInfo->getPoiSeq());
        $this->assertNull($orderInfo->getNote());
        $this->assertNull($orderInfo->getIsDirectDelivery());
    }

    public function testFromArrayWithEmptyData(): void
    {
        $orderInfo = OrderInfo::fromArray([]);

        $this->assertNull($orderInfo->getOrderTime());
        $this->assertNull($orderInfo->getOrderType());
        $this->assertNull($orderInfo->getPoiSeq());
        $this->assertNull($orderInfo->getNote());
    }

    public function testBooleanConversionInToRequestArray(): void
    {
        $this->orderInfo->setIsDirectDelivery(true);
        $this->orderInfo->setIsPickupCodeNeeded(false);

        $array = $this->orderInfo->toRequestArray();

        $this->assertSame(1, $array['is_direct_delivery']);
        $this->assertSame(0, $array['is_pickup_code_needed']);
    }
}