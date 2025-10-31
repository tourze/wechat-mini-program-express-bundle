<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\Request;

use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 重新下单请求
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/reOrder.html
 */
class ReOrderRequest extends WithAccountRequest
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

    public function getRequestPath(): string
    {
        return '/cgi-bin/express/local/business/order/readd';
    }

    public function getRequestOptions(): ?array
    {
        return [
            'json' => [
                'order_id' => $this->order_id,
                'delivery_id' => $this->delivery_id,
                'shop_id' => $this->shop_id,
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
}
