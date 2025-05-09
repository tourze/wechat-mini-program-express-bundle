<?php

namespace WechatMiniProgramExpressBundle\Request;

use WechatMiniProgramBundle\Request\WithAccountRequest;
use WechatMiniProgramExpressBundle\Entity\Embed\CargoInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\OrderInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\ReceiverInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\SenderInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\ShopInfo;

/**
 * 下配送单请求
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/addLocalOrder.html
 */
class AddOrderRequest extends WithAccountRequest
{
    /**
     * 商家id， 由配送公司分配的appkey
     */
    private string $shopid = '';

    /**
     * 唯一标识订单的ID，由商户生成
     */
    private string $shop_order_id = '';

    /**
     * 配送公司ID
     */
    private string $delivery_id = '';

    /**
     * 商家门店编号，在配送公司登记过的门店编号
     */
    private ?string $shop_no = null;

    /**
     * 下单用户的openid
     */
    private ?string $openid = null;

    /**
     * 商家分配的业务单号
     */
    private ?string $sub_biz_id = null;

    /**
     * 配送公司为商家分配的安全码
     */
    private ?string $delivery_sign = null;

    /**
     * 配送公司回调商家的配送结果的回调token，用于保障安全性
     */
    private ?string $delivery_token = null;

    /**
     * 发件人信息
     */
    private ?SenderInfo $sender = null;

    /**
     * 收件人信息
     */
    private ?ReceiverInfo $receiver = null;

    /**
     * 货物信息
     */
    private ?CargoInfo $cargo = null;

    /**
     * 订单信息
     */
    private ?OrderInfo $orderInfo = null;

    /**
     * 商品信息，会展示到物流通知消息中
     */
    private ?ShopInfo $shop = null;

    public function getRequestPath(): string
    {
        return '/cgi-bin/express/local/business/order/add';
    }

    public function getRequestOptions(): ?array
    {
        $params = $this->buildRequestParams();

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
     * 获取商家ID
     */
    public function getShopId(): string
    {
        return $this->shopid;
    }

    /**
     * 设置商家订单ID
     */
    public function setShopOrderId(string $shop_order_id): self
    {
        $this->shop_order_id = $shop_order_id;

        return $this;
    }

    /**
     * 获取商家订单ID
     */
    public function getShopOrderId(): string
    {
        return $this->shop_order_id;
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
     * 获取配送公司ID
     */
    public function getDeliveryId(): string
    {
        return $this->delivery_id;
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
     * 获取商家门店编号
     */
    public function getShopNo(): ?string
    {
        return $this->shop_no;
    }

    /**
     * 设置下单用户openid
     */
    public function setOpenid(?string $openid): self
    {
        $this->openid = $openid;

        return $this;
    }

    /**
     * 设置商家分配的业务单号
     */
    public function setSubBizId(?string $sub_biz_id): self
    {
        $this->sub_biz_id = $sub_biz_id;

        return $this;
    }

    /**
     * 设置配送公司安全码
     */
    public function setDeliverySign(?string $delivery_sign): self
    {
        $this->delivery_sign = $delivery_sign;

        return $this;
    }

    /**
     * 设置配送公司回调token
     */
    public function setDeliveryToken(?string $delivery_token): self
    {
        $this->delivery_token = $delivery_token;

        return $this;
    }

    /**
     * 设置发件人信息
     */
    public function setSender(SenderInfo $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * 设置收件人信息
     */
    public function setReceiver(ReceiverInfo $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * 设置货物信息
     */
    public function setCargo(CargoInfo $cargo): self
    {
        $this->cargo = $cargo;

        return $this;
    }

    /**
     * 设置订单信息
     */
    public function setOrderInfo(OrderInfo $orderInfo): self
    {
        $this->orderInfo = $orderInfo;

        return $this;
    }

    /**
     * 设置商品信息
     */
    public function setShop(?ShopInfo $shop): self
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * 构建请求参数
     */
    private function buildRequestParams(): array
    {
        $params = [
            'shopid' => $this->shopid,
            'shop_order_id' => $this->shop_order_id,
            'delivery_id' => $this->delivery_id,
        ];

        // 添加发件人信息
        if ($this->sender) {
            $params['sender'] = $this->sender->toRequestArray();
        }

        // 添加收件人信息
        if ($this->receiver) {
            $params['receiver'] = $this->receiver->toRequestArray();
        }

        // 添加货物信息
        if ($this->cargo) {
            $params['cargo'] = $this->cargo->toRequestArray();
        }

        // 添加订单信息
        if ($this->orderInfo) {
            $params['order_info'] = $this->orderInfo->toRequestArray();
        }

        // 添加商品信息
        if ($this->shop) {
            $params['shop'] = $this->shop->toRequestArray();
        }

        // 添加可选参数
        if ($this->shop_no) {
            $params['shop_no'] = $this->shop_no;
        }

        if ($this->openid) {
            $params['openid'] = $this->openid;
        }

        if ($this->sub_biz_id) {
            $params['sub_biz_id'] = $this->sub_biz_id;
        }

        if ($this->delivery_sign) {
            $params['delivery_sign'] = $this->delivery_sign;
        }

        if ($this->delivery_token) {
            $params['delivery_token'] = $this->delivery_token;
        }

        return $params;
    }

    /**
     * 获取请求路径，兼容测试
     */
    public function getPath(): string
    {
        return '/cgi-bin/express/local/business/order/add';
    }

    /**
     * 获取API类型，兼容测试
     */
    public function getAppApiType(): string
    {
        return 'miniprogram';
    }

    /**
     * 是否需要访问令牌，兼容测试
     */
    public function isRequireAccessToken(): bool
    {
        return true;
    }

    /**
     * 转换为数组，兼容测试
     */
    public function toArray(): array
    {
        return $this->buildRequestParams();
    }
}
