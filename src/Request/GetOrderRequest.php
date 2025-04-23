<?php

namespace WechatMiniProgramExpressBundle\Request;

use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 获取订单请求
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/getOrder.html
 */
class GetOrderRequest extends WithAccountRequest
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
        return '/cgi-bin/express/local/business/order/get';
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
    public function setOrderId(string $order_id): self
    {
        $this->order_id = $order_id;

        return $this;
    }

    /**
     * 设置配送公司ID
     */
    public function setDeliveryId(string $delivery_id): self
    {
        $this->delivery_id = $delivery_id;

        return $this;
    }

    /**
     * 设置商户ID
     */
    public function setShopId(string $shop_id): self
    {
        $this->shop_id = $shop_id;

        return $this;
    }
}
