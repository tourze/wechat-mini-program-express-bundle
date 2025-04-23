<?php

namespace WechatMiniProgramExpressBundle\Request;

use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 预下配送单请求
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/preAddOrder.html
 */
class PreAddOrderRequest extends WithAccountRequest
{
    /**
     * 商家ID，由配送公司分配的唯一ID
     */
    private string $shopid = '';

    /**
     * 商家门店编号，在配送公司侧登记
     */
    private ?string $shop_no = null;

    /**
     * 配送公司ID
     */
    private string $delivery_id = '';

    /**
     * 商家订单号
     */
    private string $shop_order_id = '';

    /**
     * 商品信息
     */
    private array $cargo = [];

    /**
     * 收件人信息
     */
    private array $receiver = [];

    /**
     * 发件人信息
     */
    private array $sender = [];

    /**
     * 订单信息
     */
    private array $order_info = [];

    /**
     * 店铺信息，闪送等要求必须提供
     */
    private ?array $shop = null;

    public function getRequestPath(): string
    {
        return '/cgi-bin/express/local/business/order/pre_add';
    }

    public function getRequestOptions(): ?array
    {
        $params = [
            'shopid' => $this->shopid,
            'delivery_id' => $this->delivery_id,
            'shop_order_id' => $this->shop_order_id,
            'sender' => $this->sender,
            'receiver' => $this->receiver,
            'cargo' => $this->cargo,
            'order_info' => $this->order_info,
        ];

        if (null !== $this->shop_no) {
            $params['shop_no'] = $this->shop_no;
        }

        if (null !== $this->shop) {
            $params['shop'] = $this->shop;
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
     * 设置商家门店编号
     */
    public function setShopNo(?string $shop_no): self
    {
        $this->shop_no = $shop_no;

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
     * 设置商家订单号
     */
    public function setShopOrderId(string $shop_order_id): self
    {
        $this->shop_order_id = $shop_order_id;

        return $this;
    }

    /**
     * 设置商品信息
     */
    public function setCargo(array $cargo): self
    {
        $this->cargo = $cargo;

        return $this;
    }

    /**
     * 设置收件人信息
     */
    public function setReceiver(array $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * 设置发件人信息
     */
    public function setSender(array $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * 设置订单信息
     */
    public function setOrderInfo(array $order_info): self
    {
        $this->order_info = $order_info;

        return $this;
    }

    /**
     * 设置店铺信息
     */
    public function setShop(?array $shop): self
    {
        $this->shop = $shop;

        return $this;
    }
}
