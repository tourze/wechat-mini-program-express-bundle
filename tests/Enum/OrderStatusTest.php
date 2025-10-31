<?php

namespace WechatMiniProgramExpressBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatMiniProgramExpressBundle\Enum\OrderStatus;

/**
 * @internal
 */
#[CoversClass(OrderStatus::class)]
final class OrderStatusTest extends AbstractEnumTestCase
{
    public function testGetCategoryForDispatchingStatus(): void
    {
        $this->assertSame(OrderStatus::ORDER_STATUS_DISPATCHING, OrderStatus::DELIVERY_DISPATCHING->getCategory());
        $this->assertSame(OrderStatus::ORDER_STATUS_DISPATCHING, OrderStatus::DELIVERY_ACCEPTED->getCategory());
        $this->assertSame(OrderStatus::ORDER_STATUS_DISPATCHING, OrderStatus::DELIVERY_ARRIVED_PICKUP->getCategory());
        $this->assertSame(OrderStatus::ORDER_STATUS_DISPATCHING, OrderStatus::DELIVERY_PICKUP_DELIVER->getCategory());
    }

    public function testGetCategoryForFinishedStatus(): void
    {
        $this->assertSame(OrderStatus::ORDER_STATUS_FINISHED, OrderStatus::DELIVERY_DELIVERED->getCategory());
        $this->assertSame(OrderStatus::ORDER_STATUS_FINISHED, OrderStatus::DELIVERY_CONFIRMED->getCategory());
        $this->assertSame(OrderStatus::ORDER_STATUS_FINISHED, OrderStatus::DELIVERY_REJECTED->getCategory());
    }

    public function testGetCategoryForCancelledStatus(): void
    {
        $this->assertSame(OrderStatus::ORDER_STATUS_CANCELLED, OrderStatus::DELIVERY_CANCELLED->getCategory());
        $this->assertSame(OrderStatus::ORDER_STATUS_CANCELLED, OrderStatus::DELIVERY_CANCELLED_EXCEPTION->getCategory());
        $this->assertSame(OrderStatus::ORDER_STATUS_CANCELLED, OrderStatus::DELIVERY_CANCELLED_DRIVER->getCategory());
    }

    public function testGetCategoryForExceptionStatus(): void
    {
        $this->assertSame(OrderStatus::ORDER_STATUS_EXCEPTION, OrderStatus::DELIVERY_EXCEPTION->getCategory());
        $this->assertSame(OrderStatus::ORDER_STATUS_EXCEPTION, OrderStatus::DELIVERY_RETURNING->getCategory());
        $this->assertSame(OrderStatus::ORDER_STATUS_EXCEPTION, OrderStatus::DELIVERY_RETURNED->getCategory());
    }

    public function testGetCategoryForTopLevelStatus(): void
    {
        $this->assertSame(OrderStatus::ORDER_STATUS_DISPATCHING, OrderStatus::ORDER_STATUS_DISPATCHING->getCategory());
        $this->assertSame(OrderStatus::ORDER_STATUS_FINISHED, OrderStatus::ORDER_STATUS_FINISHED->getCategory());
        $this->assertSame(OrderStatus::ORDER_STATUS_CANCELLED, OrderStatus::ORDER_STATUS_CANCELLED->getCategory());
        $this->assertSame(OrderStatus::ORDER_STATUS_EXCEPTION, OrderStatus::ORDER_STATUS_EXCEPTION->getCategory());
    }

    public function testGetDescription(): void
    {
        $this->assertSame('配送中', OrderStatus::ORDER_STATUS_DISPATCHING->getDescription());
        $this->assertSame('已完成', OrderStatus::ORDER_STATUS_FINISHED->getDescription());
        $this->assertSame('已取消', OrderStatus::ORDER_STATUS_CANCELLED->getDescription());
        $this->assertSame('异常', OrderStatus::ORDER_STATUS_EXCEPTION->getDescription());

        $this->assertSame('配送单创建, 待分配骑手', OrderStatus::DELIVERY_DISPATCHING->getDescription());
        $this->assertSame('骑手已接单', OrderStatus::DELIVERY_ACCEPTED->getDescription());
        $this->assertSame('骑手已到店', OrderStatus::DELIVERY_ARRIVED_PICKUP->getDescription());
        $this->assertSame('骑手已取货，配送中', OrderStatus::DELIVERY_PICKUP_DELIVER->getDescription());

        $this->assertSame('骑手已送达', OrderStatus::DELIVERY_DELIVERED->getDescription());
        $this->assertSame('已妥投', OrderStatus::DELIVERY_CONFIRMED->getDescription());
        $this->assertSame('用户拒收', OrderStatus::DELIVERY_REJECTED->getDescription());

        $this->assertSame('订单取消', OrderStatus::DELIVERY_CANCELLED->getDescription());
        $this->assertSame('订单异常取消', OrderStatus::DELIVERY_CANCELLED_EXCEPTION->getDescription());
        $this->assertSame('骑手主动取消', OrderStatus::DELIVERY_CANCELLED_DRIVER->getDescription());

        $this->assertSame('配送异常', OrderStatus::DELIVERY_EXCEPTION->getDescription());
        $this->assertSame('退回中', OrderStatus::DELIVERY_RETURNING->getDescription());
        $this->assertSame('已退回', OrderStatus::DELIVERY_RETURNED->getDescription());
    }

    public function testToLabel(): void
    {
        $this->assertSame('配送中', OrderStatus::ORDER_STATUS_DISPATCHING->toLabel());
        $this->assertSame('已完成', OrderStatus::ORDER_STATUS_FINISHED->toLabel());
        $this->assertSame('骑手已接单', OrderStatus::DELIVERY_ACCEPTED->toLabel());
    }

    public function testGetLabel(): void
    {
        $this->assertSame('配送中', OrderStatus::ORDER_STATUS_DISPATCHING->getLabel());
        $this->assertSame('已完成', OrderStatus::ORDER_STATUS_FINISHED->getLabel());
        $this->assertSame('骑手已接单', OrderStatus::DELIVERY_ACCEPTED->getLabel());
    }

    public function testLabelAndDescriptionAreConsistent(): void
    {
        foreach (OrderStatus::cases() as $status) {
            $this->assertSame($status->getDescription(), $status->toLabel());
            $this->assertSame($status->getDescription(), $status->getLabel());
        }
    }

    public function testImplementsInterfaces(): void
    {
        $this->assertInstanceOf(Labelable::class, OrderStatus::ORDER_STATUS_DISPATCHING);
        $this->assertInstanceOf(Itemable::class, OrderStatus::ORDER_STATUS_DISPATCHING);
        $this->assertInstanceOf(Selectable::class, OrderStatus::ORDER_STATUS_DISPATCHING);
    }

    public function testToArray(): void
    {
        // 测试 toArray 方法，它来自 ItemTrait
        $result = OrderStatus::ORDER_STATUS_DISPATCHING->toArray();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('value', $result);
        $this->assertArrayHasKey('label', $result);

        $this->assertSame(10, $result['value']);
        $this->assertSame('配送中', $result['label']);

        // 测试其他枚举值
        $finishedResult = OrderStatus::ORDER_STATUS_FINISHED->toArray();
        $this->assertSame(20, $finishedResult['value']);
        $this->assertSame('已完成', $finishedResult['label']);

        // 测试详细状态
        $deliveryResult = OrderStatus::DELIVERY_DISPATCHING->toArray();
        $this->assertSame(100, $deliveryResult['value']);
        $this->assertSame('配送单创建, 待分配骑手', $deliveryResult['label']);
    }

    #[TestWith([OrderStatus::ORDER_STATUS_DISPATCHING, 10, '配送中'])]
    #[TestWith([OrderStatus::ORDER_STATUS_FINISHED, 20, '已完成'])]
    #[TestWith([OrderStatus::ORDER_STATUS_CANCELLED, 30, '已取消'])]
    #[TestWith([OrderStatus::ORDER_STATUS_EXCEPTION, 40, '异常'])]
    #[TestWith([OrderStatus::DELIVERY_DISPATCHING, 100, '配送单创建, 待分配骑手'])]
    #[TestWith([OrderStatus::DELIVERY_ACCEPTED, 101, '骑手已接单'])]
    #[TestWith([OrderStatus::DELIVERY_ARRIVED_PICKUP, 102, '骑手已到店'])]
    #[TestWith([OrderStatus::DELIVERY_PICKUP_DELIVER, 103, '骑手已取货，配送中'])]
    #[TestWith([OrderStatus::DELIVERY_DELIVERED, 200, '骑手已送达'])]
    #[TestWith([OrderStatus::DELIVERY_CONFIRMED, 201, '已妥投'])]
    #[TestWith([OrderStatus::DELIVERY_REJECTED, 202, '用户拒收'])]
    #[TestWith([OrderStatus::DELIVERY_CANCELLED, 300, '订单取消'])]
    #[TestWith([OrderStatus::DELIVERY_CANCELLED_EXCEPTION, 301, '订单异常取消'])]
    #[TestWith([OrderStatus::DELIVERY_CANCELLED_DRIVER, 302, '骑手主动取消'])]
    #[TestWith([OrderStatus::DELIVERY_EXCEPTION, 400, '配送异常'])]
    #[TestWith([OrderStatus::DELIVERY_RETURNING, 401, '退回中'])]
    #[TestWith([OrderStatus::DELIVERY_RETURNED, 402, '已退回'])]
    public function testValueAndLabelMapping(OrderStatus $status, int $expectedValue, string $expectedLabel): void
    {
        $this->assertSame($expectedValue, $status->value);
        $this->assertSame($expectedLabel, $status->getLabel());
    }

    public function testFromMethodWithValidValue(): void
    {
        $this->assertSame(OrderStatus::ORDER_STATUS_DISPATCHING, OrderStatus::from(10));
        $this->assertSame(OrderStatus::DELIVERY_DISPATCHING, OrderStatus::from(100));
        $this->assertSame(OrderStatus::DELIVERY_RETURNED, OrderStatus::from(402));
    }

    public function testFromMethodWithInvalidValue(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('999 is not a valid backing value for enum WechatMiniProgramExpressBundle\Enum\OrderStatus');
        OrderStatus::from(999);
    }

    public function testTryFromMethodWithValidValue(): void
    {
        $this->assertSame(OrderStatus::ORDER_STATUS_DISPATCHING, OrderStatus::tryFrom(10));
        $this->assertSame(OrderStatus::DELIVERY_DISPATCHING, OrderStatus::tryFrom(100));
        $this->assertSame(OrderStatus::DELIVERY_RETURNED, OrderStatus::tryFrom(402));
    }

    public function testTryFromMethodWithInvalidValue(): void
    {
        $this->assertNull(OrderStatus::tryFrom(999));
        $this->assertNull(OrderStatus::tryFrom(-1));
        $this->assertNull(OrderStatus::tryFrom(500));
    }

    public function testValueUniqueness(): void
    {
        $values = array_map(fn ($case) => $case->value, OrderStatus::cases());
        $this->assertSame(count($values), count(array_unique($values)), '所有枚举的 value 必须是唯一的。');
    }

    public function testLabelUniqueness(): void
    {
        $labels = array_map(fn ($case) => $case->getLabel(), OrderStatus::cases());
        $this->assertSame(count($labels), count(array_unique($labels)), '所有枚举的 label 必须是唯一的。');
    }
}
