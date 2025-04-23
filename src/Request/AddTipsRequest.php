<?php

namespace WechatMiniProgramExpressBundle\Request;

use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 增加小费请求
 *
 * 该接口可以对待接单状态的订单增加小费。需要注意：订单的小费，以最新一次加小费动作的金额为准，
 * 故下一次增加小费额必须大于上一次小费额。
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/addTips.html
 */
class AddTipsRequest extends WithAccountRequest
{
    /**
     * 商家id，由配送公司分配的appkey
     */
    private string $shopid = '';

    /**
     * 唯一标识订单的ID，由商户生成
     */
    private string $shop_order_id = '';

    /**
     * 配送单id
     */
    private string $waybill_id = '';

    /**
     * 小费金额，单位为元
     */
    private float $tips = 0.0;

    /**
     * 配送公司为商家分配的安全码
     */
    private string $delivery_sign = '';

    /**
     * 商家门店编号，在配送公司登记，闪送必填，值为店铺id
     */
    private ?string $shop_no = null;

    /**
     * 备注
     */
    private ?string $remark = null;

    public function getRequestPath(): string
    {
        return '/cgi-bin/express/local/business/order/addtips';
    }

    public function getRequestOptions(): ?array
    {
        $params = [
            'shopid' => $this->shopid,
            'shop_order_id' => $this->shop_order_id,
            'waybill_id' => $this->waybill_id,
            'tips' => $this->tips,
            'delivery_sign' => $this->delivery_sign,
        ];

        if (null !== $this->shop_no) {
            $params['shop_no'] = $this->shop_no;
        }

        if (null !== $this->remark) {
            $params['remark'] = $this->remark;
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
     * 设置商家订单ID
     */
    public function setShopOrderId(string $shop_order_id): self
    {
        $this->shop_order_id = $shop_order_id;

        return $this;
    }

    /**
     * 设置配送单ID
     */
    public function setWaybillId(string $waybill_id): self
    {
        $this->waybill_id = $waybill_id;

        return $this;
    }

    /**
     * 设置小费金额
     */
    public function setTips(float $tips): self
    {
        $this->tips = $tips;

        return $this;
    }

    /**
     * 设置配送公司安全码
     */
    public function setDeliverySign(string $delivery_sign): self
    {
        $this->delivery_sign = $delivery_sign;

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
     * 设置备注
     */
    public function setRemark(?string $remark): self
    {
        $this->remark = $remark;

        return $this;
    }
}
