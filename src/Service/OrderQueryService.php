<?php

namespace WechatMiniProgramExpressBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramExpressBundle\Exception\DeliveryException;
use WechatMiniProgramExpressBundle\Repository\OrderRepository;
use WechatMiniProgramExpressBundle\Request\AbnormalConfirmRequest;
use WechatMiniProgramExpressBundle\Request\AddTipsRequest;
use WechatMiniProgramExpressBundle\Request\GetOrderRequest;

/**
 * 微信小程序即时配送订单查询服务
 *
 * 负责配送订单的查询和异常处理
 */
#[Autoconfigure(lazy: true, public: true)]
class OrderQueryService
{
    public function __construct(
        private readonly Client $client,
        private readonly OrderRepository $orderRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * 查询订单
     */
    public function getOrder(string $wechatOrderId): array
    {
        try {
            $order = $this->orderRepository->findByWechatOrderId($wechatOrderId);
            if (!$order) {
                throw new DeliveryException('订单不存在');
            }

            $params = [
                'order_id' => $wechatOrderId,
                'delivery_id' => $order->getDeliveryCompanyId(),
                'shop_id' => $order->getBindAccountId(),
            ];
            $order->setRequestData($params);

            $request = new GetOrderRequest();
            $request->setOrderId($wechatOrderId)
                ->setDeliveryId($order->getDeliveryCompanyId())
                ->setShopId($order->getBindAccountId());

            $response = $this->client->request($request);
            $order->setResponseData($response);
            $order->updateFromResponse($response);
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            return $response;
        } catch  (\Throwable $e) {
            $this->logger->error('查询订单失败', [
                'exception' => $e->getMessage(),
                'order_id' => $wechatOrderId,
            ]);
            throw new DeliveryException('查询订单失败: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 增加小费
     *
     * 该接口可以对待接单状态的订单增加小费。需要注意：订单的小费，以最新一次加小费动作的金额为准，
     * 故下一次增加小费额必须大于上一次小费额。
     *
     * @param string      $wechatOrderId 微信订单ID
     * @param float       $tips          小费金额，单位元
     * @param string|null $remark        备注
     * @param string|null $deliverySign  配送公司安全码
     * @param string|null $shopNo        商家门店编号
     *
     * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/addTips.html
     */
    public function addTips(
        string $wechatOrderId,
        float $tips,
        ?string $remark = null,
        ?string $deliverySign = null,
        ?string $shopNo = null,
    ): array {
        try {
            $order = $this->orderRepository->findByWechatOrderId($wechatOrderId);
            if (!$order) {
                throw new DeliveryException('订单不存在');
            }

            $request = new AddTipsRequest();
            $request->setShopId($order->getBindAccountId())
                ->setShopOrderId($order->getOrderInfo()->getPoiSeq())
                ->setWaybillId($order->getDeliveryId() ?: '')
                ->setTips($tips)
                ->setDeliverySign($deliverySign ?: '');

            if (null !== $shopNo) {
                $request->setShopNo($shopNo);
            }

            if (null !== $remark) {
                $request->setRemark($remark);
            }

            $requestParams = [
                'shopid' => $order->getBindAccountId(),
                'shop_order_id' => $order->getOrderInfo()->getPoiSeq(),
                'waybill_id' => $order->getDeliveryId() ?: '',
                'tips' => $tips,
                'delivery_sign' => $deliverySign ?: '',
            ];

            if (null !== $shopNo) {
                $requestParams['shop_no'] = $shopNo;
            }

            if (null !== $remark) {
                $requestParams['remark'] = $remark;
            }

            $order->setRequestData($requestParams);

            $response = $this->client->request($request);
            $order->setResponseData($response);
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            return $response;
        } catch  (\Throwable $e) {
            $this->logger->error('增加小费失败', [
                'exception' => $e->getMessage(),
                'order_id' => $wechatOrderId,
                'tips' => $tips,
            ]);
            throw new DeliveryException('增加小费失败: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 确认异常件退回
     *
     * 当订单配送异常，骑手把货物退还给商家，商家收货以后调用本接口返回确认收货
     *
     * @param string      $wechatOrderId 微信订单ID
     * @param string      $waybillId     配送单ID
     * @param string|null $remark        备注
     * @param string|null $deliverySign  配送公司安全码
     * @param string|null $shopNo        商家门店编号
     *
     * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/abnormalConfirm.html
     */
    public function confirmAbnormalReturn(
        string $wechatOrderId,
        string $waybillId,
        ?string $remark = null,
        ?string $deliverySign = null,
        ?string $shopNo = null,
    ): array {
        try {
            $order = $this->orderRepository->findByWechatOrderId($wechatOrderId);
            if (!$order) {
                throw new DeliveryException('订单不存在');
            }

            $request = new AbnormalConfirmRequest();
            $request->setShopId($order->getBindAccountId())
                ->setShopOrderId($order->getOrderInfo()->getPoiSeq())
                ->setWaybillId($waybillId)
                ->setDeliverySign($deliverySign ?: '')  // 安全码
                ->setShopNo($shopNo ?: '');  // 商家门店编号

            if (null !== $remark) {
                $request->setRemark($remark);
            }

            $requestParams = [
                'shopid' => $order->getBindAccountId(),
                'shop_order_id' => $order->getOrderInfo()->getPoiSeq(),
                'waybill_id' => $waybillId,
                'delivery_sign' => $deliverySign ?: '',
                'shop_no' => $shopNo ?: '',
            ];

            if (null !== $remark) {
                $requestParams['remark'] = $remark;
            }

            $order->setRequestData($requestParams);

            $response = $this->client->request($request);
            $order->setResponseData($response);
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            return $response;
        } catch  (\Throwable $e) {
            $this->logger->error('确认异常件退回失败', [
                'exception' => $e->getMessage(),
                'order_id' => $wechatOrderId,
                'waybill_id' => $waybillId,
            ]);
            throw new DeliveryException('确认异常件退回失败: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }
}
