<?php

namespace WechatMiniProgramExpressBundle\Tests\Entity\Embed;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Entity\Embed\OrderInfo;

/**
 * @internal
 */
#[CoversClass(OrderInfo::class)]
final class OrderInfoTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $orderInfo = new OrderInfo();

        $orderInfo->setOrderTime(1234567890);
        $this->assertSame(1234567890, $orderInfo->getOrderTime());

        $orderInfo->setOrderType(1);
        $this->assertSame(1, $orderInfo->getOrderType());

        $orderInfo->setPoiSeq('POI123');
        $this->assertSame('POI123', $orderInfo->getPoiSeq());

        $orderInfo->setNote('测试备注');
        $this->assertSame('测试备注', $orderInfo->getNote());

        $orderInfo->setIsDirectDelivery(true);
        $this->assertTrue($orderInfo->getIsDirectDelivery());

        $orderInfo->setIsPickupCodeNeeded(false);
        $this->assertFalse($orderInfo->getIsPickupCodeNeeded());

        $orderInfo->setIsFinishCodeNeeded(true);
        $this->assertTrue($orderInfo->getIsFinishCodeNeeded());

        $orderInfo->setExpectedDeliveryTime(1234567999);
        $this->assertSame(1234567999, $orderInfo->getExpectedDeliveryTime());

        $orderInfo->setDeliveryServiceCode('CODE123');
        $this->assertSame('CODE123', $orderInfo->getDeliveryServiceCode());

        $orderInfo->setIsInsured(false);
        $this->assertFalse($orderInfo->getIsInsured());

        $orderInfo->setTips(5.5);
        $this->assertSame(5.5, $orderInfo->getTips());
    }

    public function testToRequestArray(): void
    {
        $orderInfo = new OrderInfo();
        $orderInfo->setOrderTime(1234567890);
        $orderInfo->setOrderType(1);
        $orderInfo->setPoiSeq('POI123');
        $orderInfo->setNote('测试备注');
        $orderInfo->setIsDirectDelivery(true);
        $orderInfo->setIsPickupCodeNeeded(false);
        $orderInfo->setIsFinishCodeNeeded(true);
        $orderInfo->setExpectedDeliveryTime(1234567999);
        $orderInfo->setDeliveryServiceCode('CODE123');
        $orderInfo->setIsInsured(false);
        $orderInfo->setTips(5.5);

        $array = $orderInfo->toRequestArray();

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
        $orderInfo = new OrderInfo();
        $orderInfo->setOrderTime(1234567890);
        $orderInfo->setOrderType(1);
        // 布尔值保持null

        $array = $orderInfo->toRequestArray();

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
        $orderInfo = new OrderInfo();
        $orderInfo->setIsDirectDelivery(true);
        $orderInfo->setIsPickupCodeNeeded(false);

        $array = $orderInfo->toRequestArray();

        $this->assertSame(1, $array['is_direct_delivery']);
        $this->assertSame(0, $array['is_pickup_code_needed']);
    }
}
