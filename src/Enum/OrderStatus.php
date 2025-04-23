<?php

namespace WechatMiniProgramExpressBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * 配送单状态枚举
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/industry/immediate-delivery/order_status.html
 */
enum OrderStatus: int implements Labelable, Itemable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    // 订单状态分类: 配送中
    case ORDER_STATUS_DISPATCHING = 10;

    // 订单状态分类: 已完成
    case ORDER_STATUS_FINISHED = 20;

    // 订单状态分类: 已取消
    case ORDER_STATUS_CANCELLED = 30;

    // 订单状态分类: 异常
    case ORDER_STATUS_EXCEPTION = 40;

    // 详细状态码: 配送中
    case DELIVERY_DISPATCHING = 100;
    case DELIVERY_ACCEPTED = 101;
    case DELIVERY_ARRIVED_PICKUP = 102;
    case DELIVERY_PICKUP_DELIVER = 103;

    // 详细状态码: 已完成
    case DELIVERY_DELIVERED = 200;
    case DELIVERY_CONFIRMED = 201;
    case DELIVERY_REJECTED = 202;

    // 详细状态码: 已取消
    case DELIVERY_CANCELLED = 300;
    case DELIVERY_CANCELLED_EXCEPTION = 301;
    case DELIVERY_CANCELLED_DRIVER = 302;

    // 详细状态码: 异常
    case DELIVERY_EXCEPTION = 400;
    case DELIVERY_RETURNING = 401;
    case DELIVERY_RETURNED = 402;

    /**
     * 获取状态码类别
     */
    public function getCategory(): self
    {
        return match (true) {
            $this->value >= 100 && $this->value < 200 => self::ORDER_STATUS_DISPATCHING,
            $this->value >= 200 && $this->value < 300 => self::ORDER_STATUS_FINISHED,
            $this->value >= 300 && $this->value < 400 => self::ORDER_STATUS_CANCELLED,
            $this->value >= 400 && $this->value < 500 => self::ORDER_STATUS_EXCEPTION,
            default => $this,
        };
    }

    /**
     * 获取标签文本
     */
    public function toLabel(): string
    {
        return $this->getDescription();
    }

    /**
     * 获取标签
     */
    public function getLabel(): string
    {
        return $this->getDescription();
    }

    /**
     * 获取状态描述
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::ORDER_STATUS_DISPATCHING => '配送中',
            self::ORDER_STATUS_FINISHED => '已完成',
            self::ORDER_STATUS_CANCELLED => '已取消',
            self::ORDER_STATUS_EXCEPTION => '异常',
            self::DELIVERY_DISPATCHING => '配送单创建, 待分配骑手',
            self::DELIVERY_ACCEPTED => '骑手已接单',
            self::DELIVERY_ARRIVED_PICKUP => '骑手已到店',
            self::DELIVERY_PICKUP_DELIVER => '骑手已取货，配送中',
            self::DELIVERY_DELIVERED => '骑手已送达',
            self::DELIVERY_CONFIRMED => '已妥投',
            self::DELIVERY_REJECTED => '用户拒收',
            self::DELIVERY_CANCELLED => '订单取消',
            self::DELIVERY_CANCELLED_EXCEPTION => '订单异常取消',
            self::DELIVERY_CANCELLED_DRIVER => '骑手主动取消',
            self::DELIVERY_EXCEPTION => '配送异常',
            self::DELIVERY_RETURNING => '退回中',
            self::DELIVERY_RETURNED => '已退回',
        };
    }
}
