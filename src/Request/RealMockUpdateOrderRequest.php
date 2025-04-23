<?php

namespace WechatMiniProgramExpressBundle\Request;

use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 模拟配送公司更新配送单状态请求
 *
 * 该接口用于模拟配送公司更新配送单状态，可进行测试账户下的单，将请求转发到运力测试环境。
 * 该接口只能用于测试，请求会转发到运力测试环境，目前支持顺丰同城和达达。
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/realMockUpdateOrder.html
 */
class RealMockUpdateOrderRequest extends WithAccountRequest
{
    /**
     * 商家ID，由配送公司分配的唯一ID
     */
    private string $shopid = '';

    /**
     * 商家订单号
     */
    private string $shop_order_id = '';

    /**
     * 配送状态
     */
    private int $order_status = 0;

    /**
     * 状态变更时间点，Unix秒级时间戳
     */
    private int $action_time = 0;

    /**
     * 附加信息
     */
    private ?string $action_msg = null;

    /**
     * 用配送公司提供的appSecret加密的校验串
     */
    private string $delivery_sign = '';

    public function getRequestPath(): string
    {
        return '/cgi-bin/express/local/business/realmock_update_order';
    }

    public function getRequestOptions(): ?array
    {
        $params = [
            'shopid' => $this->shopid,
            'shop_order_id' => $this->shop_order_id,
            'order_status' => $this->order_status,
            'action_time' => $this->action_time,
            'delivery_sign' => $this->delivery_sign,
        ];

        if (null !== $this->action_msg) {
            $params['action_msg'] = $this->action_msg;
        }

        return [
            'json' => $params,
        ];
    }

    public function getRequestMethod(): ?string
    {
        return 'POST';
    }

    /**
     * 设置商家ID
     */
    public function setShopId(string $shopid): self
    {
        $this->shopid = $shopid;

        return $this;
    }

    /**
     * 设置商家订单号
     */
    public function setShopOrderId(string $shop_order_id): self
    {
        $this->shop_order_id = $shop_order_id;

        return $this;
    }

    /**
     * 设置配送状态
     */
    public function setOrderStatus(int $order_status): self
    {
        $this->order_status = $order_status;

        return $this;
    }

    /**
     * 设置状态变更时间
     */
    public function setActionTime(int $action_time): self
    {
        $this->action_time = $action_time;

        return $this;
    }

    /**
     * 设置附加信息
     */
    public function setActionMsg(?string $action_msg): self
    {
        $this->action_msg = $action_msg;

        return $this;
    }

    /**
     * 设置配送公司校验串
     */
    public function setDeliverySign(string $delivery_sign): self
    {
        $this->delivery_sign = $delivery_sign;

        return $this;
    }
}
