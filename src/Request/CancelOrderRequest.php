<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\Request;

use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 取消配送单请求
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/cancelOrder.html
 */
class CancelOrderRequest extends WithAccountRequest
{
    /**
     * 订单ID
     */
    private string $order_id = '';

    /**
     * 配送公司ID
     */
    private string $delivery_id = '';

    /**
     * 商户ID
     */
    private string $shop_id = '';

    /**
     * 取消原因ID，1-6分别对应取消原因列表的顺序
     * 0表示其他原因
     */
    private int $cancel_reason_id = 0;

    /**
     * 取消原因
     */
    private string $cancel_reason = '';

    public function getRequestPath(): string
    {
        return '/cgi-bin/express/local/business/order/cancel';
    }

    public function getRequestOptions(): ?array
    {
        return [
            'json' => [
                'order_id' => $this->order_id,
                'delivery_id' => $this->delivery_id,
                'shop_id' => $this->shop_id,
                'cancel_reason_id' => $this->cancel_reason_id,
                'cancel_reason' => $this->cancel_reason,
            ],
        ];
    }

    public function getRequestMethod(): ?string
    {
        return 'POST';
    }

    /**
     * 设置订单ID
     */
    public function setOrderId(string $order_id): void
    {
        $this->order_id = $order_id;
    }

    /**
     * 设置配送公司ID
     */
    public function setDeliveryId(string $delivery_id): void
    {
        $this->delivery_id = $delivery_id;
    }

    /**
     * 设置商户ID
     */
    public function setShopId(string $shop_id): void
    {
        $this->shop_id = $shop_id;
    }

    /**
     * 设置取消原因ID
     */
    public function setCancelReasonId(int $cancel_reason_id): void
    {
        $this->cancel_reason_id = $cancel_reason_id;
    }

    /**
     * 设置取消原因
     */
    public function setCancelReason(string $cancel_reason): void
    {
        $this->cancel_reason = $cancel_reason;
    }
}
