<?php

declare(strict_types=1);

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
    public function setShopId(string $shopid): void
    {
        $this->shopid = $shopid;
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
    public function setShopOrderId(string $shop_order_id): void
    {
        $this->shop_order_id = $shop_order_id;
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
    public function setDeliveryId(string $delivery_id): void
    {
        $this->delivery_id = $delivery_id;
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
    public function setShopNo(?string $shop_no): void
    {
        $this->shop_no = $shop_no;
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
    public function setOpenid(?string $openid): void
    {
        $this->openid = $openid;
    }

    /**
     * 设置商家分配的业务单号
     */
    public function setSubBizId(?string $sub_biz_id): void
    {
        $this->sub_biz_id = $sub_biz_id;
    }

    /**
     * 设置配送公司安全码
     */
    public function setDeliverySign(?string $delivery_sign): void
    {
        $this->delivery_sign = $delivery_sign;
    }

    /**
     * 设置配送公司回调token
     */
    public function setDeliveryToken(?string $delivery_token): void
    {
        $this->delivery_token = $delivery_token;
    }

    /**
     * 设置发件人信息
     */
    public function setSender(SenderInfo $sender): void
    {
        $this->sender = $sender;
    }

    /**
     * 设置收件人信息
     */
    public function setReceiver(ReceiverInfo $receiver): void
    {
        $this->receiver = $receiver;
    }

    /**
     * 设置货物信息
     */
    public function setCargo(CargoInfo $cargo): void
    {
        $this->cargo = $cargo;
    }

    /**
     * 设置订单信息
     */
    public function setOrderInfo(OrderInfo $orderInfo): void
    {
        $this->orderInfo = $orderInfo;
    }

    /**
     * 设置商品信息
     */
    public function setShop(?ShopInfo $shop): void
    {
        $this->shop = $shop;
    }

    /**
     * 构建请求参数
     *
     * @return array<string, mixed>
     */
    private function buildRequestParams(): array
    {
        $params = [
            'shopid' => $this->shopid,
            'shop_order_id' => $this->shop_order_id,
            'delivery_id' => $this->delivery_id,
        ];

        $this->addEntityParamsToRequest($params);
        $this->addOptionalParamsToRequest($params);

        return $params;
    }

    /**
     * 添加实体参数到请求
     *
     * @param array<string, mixed> $params
     */
    // @phpstan-ignore-next-line
    private function addEntityParamsToRequest(array &$params): void
    {
        if (null !== $this->sender) {
            $params['sender'] = $this->sender->toRequestArray();
        }

        if (null !== $this->receiver) {
            $params['receiver'] = $this->receiver->toRequestArray();
        }

        if (null !== $this->cargo) {
            $params['cargo'] = $this->cargo->toRequestArray();
        }

        if (null !== $this->orderInfo) {
            $params['order_info'] = $this->orderInfo->toRequestArray();
        }

        if (null !== $this->shop) {
            $params['shop'] = $this->shop->toRequestArray();
        }
    }

    /**
     * 添加可选参数到请求
     *
     * @param array<string, mixed> $params
     */
    // @phpstan-ignore-next-line
    private function addOptionalParamsToRequest(array &$params): void
    {
        $optionalFields = [
            'shop_no' => $this->shop_no,
            'openid' => $this->openid,
            'sub_biz_id' => $this->sub_biz_id,
            'delivery_sign' => $this->delivery_sign,
            'delivery_token' => $this->delivery_token,
        ];

        foreach ($optionalFields as $key => $value) {
            if (null !== $value) {
                $params[$key] = $value;
            }
        }
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
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->buildRequestParams();
    }
}
