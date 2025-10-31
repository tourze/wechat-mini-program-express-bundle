<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\Enum;

use Tourze\EnumExtra\BadgeInterface;
use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

enum OrderStatus: int implements Labelable, Itemable, Selectable, BadgeInterface
{
    use ItemTrait;
    use SelectTrait;
    case ORDER_STATUS_DISPATCHING = 10;
    case ORDER_STATUS_FINISHED = 20;
    case ORDER_STATUS_CANCELLED = 30;
    case ORDER_STATUS_EXCEPTION = 40;
    case DELIVERY_DISPATCHING = 100;
    case DELIVERY_ACCEPTED = 101;
    case DELIVERY_ARRIVED_PICKUP = 102;
    case DELIVERY_PICKUP_DELIVER = 103;
    case DELIVERY_DELIVERED = 200;
    case DELIVERY_CONFIRMED = 201;
    case DELIVERY_REJECTED = 202;
    case DELIVERY_CANCELLED = 300;
    case DELIVERY_CANCELLED_EXCEPTION = 301;
    case DELIVERY_CANCELLED_DRIVER = 302;
    case DELIVERY_EXCEPTION = 400;
    case DELIVERY_RETURNING = 401;
    case DELIVERY_RETURNED = 402;

    public function getLabel(): string
    {
        return $this->getDescription();
    }

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

    public function toLabel(): string
    {
        return $this->getDescription();
    }

    public function getCategory(): self
    {
        return match ($this) {
            self::ORDER_STATUS_DISPATCHING => self::ORDER_STATUS_DISPATCHING,
            self::ORDER_STATUS_FINISHED => self::ORDER_STATUS_FINISHED,
            self::ORDER_STATUS_CANCELLED => self::ORDER_STATUS_CANCELLED,
            self::ORDER_STATUS_EXCEPTION => self::ORDER_STATUS_EXCEPTION,

            self::DELIVERY_DISPATCHING => self::ORDER_STATUS_DISPATCHING,
            self::DELIVERY_ACCEPTED => self::ORDER_STATUS_DISPATCHING,
            self::DELIVERY_ARRIVED_PICKUP => self::ORDER_STATUS_DISPATCHING,
            self::DELIVERY_PICKUP_DELIVER => self::ORDER_STATUS_DISPATCHING,

            self::DELIVERY_DELIVERED => self::ORDER_STATUS_FINISHED,
            self::DELIVERY_CONFIRMED => self::ORDER_STATUS_FINISHED,
            self::DELIVERY_REJECTED => self::ORDER_STATUS_FINISHED,

            self::DELIVERY_CANCELLED => self::ORDER_STATUS_CANCELLED,
            self::DELIVERY_CANCELLED_EXCEPTION => self::ORDER_STATUS_CANCELLED,
            self::DELIVERY_CANCELLED_DRIVER => self::ORDER_STATUS_CANCELLED,

            self::DELIVERY_EXCEPTION => self::ORDER_STATUS_EXCEPTION,
            self::DELIVERY_RETURNING => self::ORDER_STATUS_EXCEPTION,
            self::DELIVERY_RETURNED => self::ORDER_STATUS_EXCEPTION,
        };
    }

    /**
     * 获取所有枚举的选项数组（用于下拉列表等）
     *
     * @return array<int, array{value: int, label: string}>
     */
    public static function toSelectItems(): array
    {
        $result = [];
        foreach (self::cases() as $case) {
            $result[] = [
                'value' => $case->value,
                'label' => $case->getLabel(),
            ];
        }

        return $result;
    }

    public function getBadge(): string
    {
        return match ($this) {
            self::ORDER_STATUS_DISPATCHING => BadgeInterface::PRIMARY,
            self::ORDER_STATUS_FINISHED => BadgeInterface::SUCCESS,
            self::ORDER_STATUS_CANCELLED => BadgeInterface::SECONDARY,
            self::ORDER_STATUS_EXCEPTION => BadgeInterface::DANGER,
            self::DELIVERY_DISPATCHING => BadgeInterface::WARNING,
            self::DELIVERY_ACCEPTED => BadgeInterface::INFO,
            self::DELIVERY_ARRIVED_PICKUP => BadgeInterface::INFO,
            self::DELIVERY_PICKUP_DELIVER => BadgeInterface::PRIMARY,
            self::DELIVERY_DELIVERED => BadgeInterface::SUCCESS,
            self::DELIVERY_CONFIRMED => BadgeInterface::SUCCESS,
            self::DELIVERY_REJECTED => BadgeInterface::WARNING,
            self::DELIVERY_CANCELLED => BadgeInterface::SECONDARY,
            self::DELIVERY_CANCELLED_EXCEPTION => BadgeInterface::DANGER,
            self::DELIVERY_CANCELLED_DRIVER => BadgeInterface::WARNING,
            self::DELIVERY_EXCEPTION => BadgeInterface::DANGER,
            self::DELIVERY_RETURNING => BadgeInterface::WARNING,
            self::DELIVERY_RETURNED => BadgeInterface::SECONDARY,
        };
    }
}
