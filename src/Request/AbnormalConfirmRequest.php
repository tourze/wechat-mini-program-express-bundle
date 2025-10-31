<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\Request;

use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 异常件退回商家确认请求
 *
 * 该接口用于异常件退回商家后商家确认收货。
 * 使用场景为，当订单配送异常，骑手把货物退还给商家，商家收货以后调用本接口返回确认收货。
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/abnormalConfirm.html
 */
class AbnormalConfirmRequest extends WithAccountRequest
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
     * 配送公司为商家分配的安全码
     */
    private string $delivery_sign = '';

    /**
     * 商家门店编号，在配送公司登记，闪送必填，值为店铺id
     */
    private string $shop_no = '';

    /**
     * 备注
     */
    private ?string $remark = null;

    public function getRequestPath(): string
    {
        return '/cgi-bin/express/local/business/order/confirm_return';
    }

    public function getRequestOptions(): ?array
    {
        $params = [
            'shopid' => $this->shopid,
            'shop_order_id' => $this->shop_order_id,
            'waybill_id' => $this->waybill_id,
            'delivery_sign' => $this->delivery_sign,
            'shop_no' => $this->shop_no,
        ];

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
    public function setShopId(string $shopid): void
    {
        $this->shopid = $shopid;
    }

    /**
     * 设置商家订单ID
     */
    public function setShopOrderId(string $shop_order_id): void
    {
        $this->shop_order_id = $shop_order_id;
    }

    /**
     * 设置配送单ID
     */
    public function setWaybillId(string $waybill_id): void
    {
        $this->waybill_id = $waybill_id;
    }

    /**
     * 设置配送公司安全码
     */
    public function setDeliverySign(string $delivery_sign): void
    {
        $this->delivery_sign = $delivery_sign;
    }

    /**
     * 设置商家门店编号
     */
    public function setShopNo(string $shop_no): void
    {
        $this->shop_no = $shop_no;
    }

    /**
     * 设置备注
     */
    public function setRemark(?string $remark): void
    {
        $this->remark = $remark;
    }
}
