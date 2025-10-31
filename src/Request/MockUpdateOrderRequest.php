<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\Request;

use WechatMiniProgramBundle\Request\WithAccountRequest;

/**
 * 模拟更新配送单状态请求
 *
 * 仅限商家完成开发后，在测试环境验证时使用。可以模拟实际的物流更新，从而操作该订单进入某一个状态。
 * 该接口只能用于沙盒环境开发测试，接入商户上线前必须去掉。
 *
 * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/mockUpdateOrder.html
 */
class MockUpdateOrderRequest extends WithAccountRequest
{
    /**
     * 配送单id
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
     * 动作
     *
     * 可选值有：
     * - OnAccept: 配送公司接单
     * - OrderPickup: 骑手取货
     * - OrderDelivery: 骑手送达
     * - OrderCancel: 配送公司取消
     * - OrderException: 配送异常
     * - OrderReturn: 物品返还
     */
    private string $action_type = '';

    /**
     * 附加信息
     */
    private ?string $mock_info = null;

    public function getRequestPath(): string
    {
        return '/cgi-bin/express/local/business/order/mock_update_order';
    }

    public function getRequestOptions(): ?array
    {
        $params = [
            'order_id' => $this->order_id,
            'delivery_id' => $this->delivery_id,
            'shop_id' => $this->shop_id,
            'action_type' => $this->action_type,
        ];

        if (null !== $this->mock_info) {
            $params['mock_info'] = $this->mock_info;
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
     * 设置配送单ID
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
     * 设置动作类型
     */
    public function setActionType(string $action_type): void
    {
        $this->action_type = $action_type;
    }

    /**
     * 设置附加信息
     */
    public function setMockInfo(?string $mock_info): void
    {
        $this->mock_info = $mock_info;
    }
}
