<?php

namespace WechatMiniProgramExpressBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramExpressBundle\Entity\Order;
use WechatMiniProgramExpressBundle\Exception\DeliveryException;
use WechatMiniProgramExpressBundle\Repository\OrderRepository;
use WechatMiniProgramExpressBundle\Request\AddOrderRequest;
use WechatMiniProgramExpressBundle\Request\AddTipsRequest;
use WechatMiniProgramExpressBundle\Request\CancelOrderRequest;
use WechatMiniProgramExpressBundle\Request\GetOrderRequest;
use WechatMiniProgramExpressBundle\Request\PreAddOrderRequest;
use WechatMiniProgramExpressBundle\Request\PreCancelOrderRequest;
use WechatMiniProgramExpressBundle\Request\ReOrderRequest;

/**
 * 微信小程序即时配送订单服务
 *
 * 负责配送订单的创建和修改操作
 */
#[Autoconfigure(lazy: true, public: true)]
class DeliveryOrderService
{
    public function __construct(
        private readonly Client $client,
        private readonly OrderRepository $orderRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * 预下单（获取配送费）
     */
    public function preAddOrder(Order $order): array
    {
        try {
            $params = $order->toRequestArray();
            $order->setRequestData($params);

            $request = new PreAddOrderRequest();

            // 设置各项参数
            $request->setShopId($params['shopid'])
                   ->setDeliveryId($params['delivery_id'])
                   ->setShopOrderId($params['shop_order_id'])
                   ->setSender($params['sender'])
                   ->setReceiver($params['receiver'])
                   ->setCargo($params['cargo'])
                   ->setOrderInfo($params['order_info']);

            // 设置可选参数
            if ((bool) isset($params['shop_no'])) {
                $request->setShopNo($params['shop_no']);
            }

            if ((bool) isset($params['shop'])) {
                $request->setShop($params['shop']);
            }

            $response = $this->client->request($request);
            $order->setResponseData($response);
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            return $response;
        } catch (\Throwable $e) {
            $this->logger->error('预下单失败', [
                'exception' => $e->getMessage(),
                'params' => $order->toRequestArray(),
            ]);
            throw new DeliveryException('预下单失败: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 下单（真实创建订单）
     */
    public function addOrder(Order $order): array
    {
        try {
            $requestParams = $order->toRequestArray();
            $order->setRequestData($requestParams);

            $request = new AddOrderRequest();
            $request->setShopId($requestParams['shopid'])
                ->setDeliveryId($requestParams['delivery_id'])
                ->setShopOrderId($requestParams['shop_order_id'])
                ->setSender($order->getSenderInfo())
                ->setReceiver($order->getReceiverInfo())
                ->setCargo($order->getCargoInfo())
                ->setOrderInfo($order->getOrderInfo());

            if ((bool) isset($requestParams['shop_no'])) {
                $request->setShopNo($requestParams['shop_no']);
            }

            $request->setShop($order->getShopInfo());

            $response = $this->client->request($request);
            $order->setResponseData($response);
            $order->updateFromResponse($response);
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            return $response;
        } catch (\Throwable $e) {
            $this->logger->error('下单失败', [
                'exception' => $e->getMessage(),
                'params' => $order->toRequestArray(),
            ]);
            throw new DeliveryException('下单失败: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 预取消订单（获取取消费用）
     */
    public function preCancelOrder(string $wechatOrderId): array
    {
        try {
            $order = $this->orderRepository->findByWechatOrderId($wechatOrderId);
            if ($order === null) {
                throw new DeliveryException('订单不存在');
            }

            $params = [
                'order_id' => $wechatOrderId,
                'delivery_id' => $order->getDeliveryCompanyId(),
                'shop_id' => $order->getBindAccountId(),
            ];
            $order->setRequestData($params);

            $request = new PreCancelOrderRequest();
            $request->setOrderId($wechatOrderId)
                   ->setDeliveryId($order->getDeliveryCompanyId())
                   ->setShopId($order->getBindAccountId());

            $response = $this->client->request($request);
            $order->setResponseData($response);
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            return $response;
        } catch (\Throwable $e) {
            $this->logger->error('预取消订单失败', [
                'exception' => $e->getMessage(),
                'order_id' => $wechatOrderId,
            ]);
            throw new DeliveryException('预取消订单失败: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 取消订单
     */
    public function cancelOrder(string $wechatOrderId, string $reason = ''): array
    {
        try {
            $order = $this->orderRepository->findByWechatOrderId($wechatOrderId);
            if ($order === null) {
                throw new DeliveryException('订单不存在');
            }

            $params = [
                'order_id' => $wechatOrderId,
                'delivery_id' => $order->getDeliveryCompanyId(),
                'shop_id' => $order->getBindAccountId(),
                'cancel_reason_id' => 0,
                'cancel_reason' => $reason ?: '商家取消',
            ];
            $order->setRequestData($params);

            $request = new CancelOrderRequest();
            $request->setOrderId($wechatOrderId)
                   ->setDeliveryId($order->getDeliveryCompanyId())
                   ->setShopId($order->getBindAccountId())
                   ->setCancelReasonId(0)
                   ->setCancelReason($reason ?: '商家取消');

            $response = $this->client->request($request);
            $order->setResponseData($response);
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            return $response;
        } catch (\Throwable $e) {
            $this->logger->error('取消订单失败', [
                'exception' => $e->getMessage(),
                'order_id' => $wechatOrderId,
            ]);
            throw new DeliveryException('取消订单失败: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 重新下单
     */
    public function reOrder(string $wechatOrderId): array
    {
        try {
            $order = $this->orderRepository->findByWechatOrderId($wechatOrderId);
            if ($order === null) {
                throw new DeliveryException('订单不存在');
            }

            $params = [
                'order_id' => $wechatOrderId,
                'delivery_id' => $order->getDeliveryCompanyId(),
                'shop_id' => $order->getBindAccountId(),
            ];
            $order->setRequestData($params);

            $request = new ReOrderRequest();
            $request->setOrderId($wechatOrderId)
                   ->setDeliveryId($order->getDeliveryCompanyId())
                   ->setShopId($order->getBindAccountId());

            $response = $this->client->request($request);
            $order->setResponseData($response);
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            return $response;
        } catch (\Throwable $e) {
            $this->logger->error('重新下单失败', [
                'exception' => $e->getMessage(),
                'order_id' => $wechatOrderId,
            ]);
            throw new DeliveryException('重新下单失败: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 查询订单
     */
    public function getOrder(string $wechatOrderId): array
    {
        try {
            $order = $this->orderRepository->findByWechatOrderId($wechatOrderId);
            if ($order === null) {
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
        } catch (\Throwable $e) {
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
     * @param Account     $account       微信账号
     * @param string      $shopid        商家ID，由配送公司分配的appkey
     * @param string      $shop_order_id 商家订单ID
     * @param string      $waybill_id    配送单ID
     * @param float       $tips          小费金额，单位元
     * @param string      $delivery_sign 配送公司安全码
     * @param string|null $shop_no       商家门店编号
     * @param string|null $remark        备注信息
     *
     * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/addTips.html
     */
    public function addTips(
        Account $account,
        string $shopid,
        string $shop_order_id,
        string $waybill_id,
        float $tips,
        string $delivery_sign,
        ?string $shop_no = null,
        ?string $remark = null,
    ): array {
        try {
            $request = new AddTipsRequest();
            $request->setAccount($account);
            $request->setShopId($shopid)
                   ->setShopOrderId($shop_order_id)
                   ->setWaybillId($waybill_id)
                   ->setTips($tips)
                   ->setDeliverySign($delivery_sign);

            if (null !== $shop_no) {
                $request->setShopNo($shop_no);
            }

            if (null !== $remark) {
                $request->setRemark($remark);
            }

            return $this->client->request($request);
        } catch (\Throwable $exception) {
            $this->logger->error('增加小费失败', [
                'exception' => $exception,
                'account' => $account,
                'shopid' => $shopid,
                'shop_order_id' => $shop_order_id,
                'waybill_id' => $waybill_id,
                'tips' => $tips,
            ]);
            throw $exception;
        }
    }
}
