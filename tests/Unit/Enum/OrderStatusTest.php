<?php

namespace WechatMiniProgramExpressBundle\Tests\Unit\Enum;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Enum\OrderStatus;

class OrderStatusTest extends TestCase
{
    public function testAllEnumCasesHaveValues(): void
    {
        $this->assertSame(10, OrderStatus::ORDER_STATUS_DISPATCHING->value);
        $this->assertSame(20, OrderStatus::ORDER_STATUS_FINISHED->value);
        $this->assertSame(30, OrderStatus::ORDER_STATUS_CANCELLED->value);
        $this->assertSame(40, OrderStatus::ORDER_STATUS_EXCEPTION->value);
        
        $this->assertSame(100, OrderStatus::DELIVERY_DISPATCHING->value);
        $this->assertSame(101, OrderStatus::DELIVERY_ACCEPTED->value);
        $this->assertSame(102, OrderStatus::DELIVERY_ARRIVED_PICKUP->value);
        $this->assertSame(103, OrderStatus::DELIVERY_PICKUP_DELIVER->value);
        
        $this->assertSame(200, OrderStatus::DELIVERY_DELIVERED->value);
        $this->assertSame(201, OrderStatus::DELIVERY_CONFIRMED->value);
        $this->assertSame(202, OrderStatus::DELIVERY_REJECTED->value);
        
        $this->assertSame(300, OrderStatus::DELIVERY_CANCELLED->value);
        $this->assertSame(301, OrderStatus::DELIVERY_CANCELLED_EXCEPTION->value);
        $this->assertSame(302, OrderStatus::DELIVERY_CANCELLED_DRIVER->value);
        
        $this->assertSame(400, OrderStatus::DELIVERY_EXCEPTION->value);
        $this->assertSame(401, OrderStatus::DELIVERY_RETURNING->value);
        $this->assertSame(402, OrderStatus::DELIVERY_RETURNED->value);
    }

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
        $this->assertInstanceOf(\Tourze\EnumExtra\Labelable::class, OrderStatus::ORDER_STATUS_DISPATCHING);
        $this->assertInstanceOf(\Tourze\EnumExtra\Itemable::class, OrderStatus::ORDER_STATUS_DISPATCHING);
        $this->assertInstanceOf(\Tourze\EnumExtra\Selectable::class, OrderStatus::ORDER_STATUS_DISPATCHING);
    }
}