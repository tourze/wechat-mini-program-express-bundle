<?php

declare(strict_types=1);

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
     * @var array<string, mixed>
     */
    private array $cargo = [];

    /**
     * 收件人信息
     * @var array<string, mixed>
     */
    private array $receiver = [];

    /**
     * 发件人信息
     * @var array<string, mixed>
     */
    private array $sender = [];

    /**
     * 订单信息
     * @var array<string, mixed>
     */
    private array $order_info = [];

    /**
     * 店铺信息，闪送等要求必须提供
     * @var array<string, mixed>|null
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
    public function setShopId(string $shopid): void
    {
        $this->shopid = $shopid;
    }

    /**
     * 设置商家门店编号
     */
    public function setShopNo(?string $shop_no): void
    {
        $this->shop_no = $shop_no;
    }

    /**
     * 设置配送公司ID
     */
    public function setDeliveryId(string $delivery_id): void
    {
        $this->delivery_id = $delivery_id;
    }

    /**
     * 设置商家订单号
     */
    public function setShopOrderId(string $shop_order_id): void
    {
        $this->shop_order_id = $shop_order_id;
    }

    /**
     * 设置商品信息
     * @param array<string, mixed> $cargo
     */
    public function setCargo(array $cargo): void
    {
        $this->cargo = $cargo;
    }

    /**
     * 设置收件人信息
     * @param array<string, mixed> $receiver
     */
    public function setReceiver(array $receiver): void
    {
        $this->receiver = $receiver;
    }

    /**
     * 设置发件人信息
     * @param array<string, mixed> $sender
     */
    public function setSender(array $sender): void
    {
        $this->sender = $sender;
    }

    /**
     * 设置订单信息
     * @param array<string, mixed> $order_info
     */
    public function setOrderInfo(array $order_info): void
    {
        $this->order_info = $order_info;
    }

    /**
     * 设置店铺信息
     * @param array<string, mixed>|null $shop
     */
    public function setShop(?array $shop): void
    {
        $this->shop = $shop;
    }
}
