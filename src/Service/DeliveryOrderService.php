<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Monolog\Attribute\WithMonologChannel;
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
#[Autoconfigure(public: true)]
#[WithMonologChannel(channel: 'wechat_mini_program_express')]
readonly class DeliveryOrderService
{
    public function __construct(
        private Client $client,
        private OrderRepository $orderRepository,
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * 预下单（获取配送费）
     *
     * @return array<string, mixed>
     */
    public function preAddOrder(Order $order): array
    {
        try {
            $params = $order->toRequestArray();
            $order->setRequestData($params);

            $request = new PreAddOrderRequest();
            $this->populatePreAddOrderRequest($request, $params);

            $response = $this->client->request($request);
            if (is_array($response)) {
                $order->setResponseData($response);
            }
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            return is_array($response) ? $response : [];
        } catch (\Throwable $e) {
            $this->logger->error('预下单失败', [
                'exception' => $e->getMessage(),
                'params' => $order->toRequestArray(),
            ]);
            throw new DeliveryException('预下单失败: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 填充预下单请求参数
     *
     * @param array<string, mixed> $params
     */
    private function populatePreAddOrderRequest(PreAddOrderRequest $request, array $params): void
    {
        $this->setRequiredParameters($request, $params);
        $this->setOptionalParameters($request, $params);
    }

    /**
     * 设置必需参数
     *
     * @param array<string, mixed> $params
     */
    private function setRequiredParameters(PreAddOrderRequest $request, array $params): void
    {
        $shopId = $params['shopid'] ?? null;
        $deliveryId = $params['delivery_id'] ?? null;
        $shopOrderId = $params['shop_order_id'] ?? null;
        $sender = $params['sender'] ?? null;
        $receiver = $params['receiver'] ?? null;
        $cargo = $params['cargo'] ?? null;
        $orderInfo = $params['order_info'] ?? null;

        if (is_string($shopId)) {
            $request->setShopId($shopId);
        }
        if (is_string($deliveryId)) {
            $request->setDeliveryId($deliveryId);
        }
        if (is_string($shopOrderId)) {
            $request->setShopOrderId($shopOrderId);
        }
        if (is_array($sender)) {
            $request->setSender($this->convertToStringKeyedArray($sender));
        }
        if (is_array($receiver)) {
            $request->setReceiver($this->convertToStringKeyedArray($receiver));
        }
        if (is_array($cargo)) {
            $request->setCargo($this->convertToStringKeyedArray($cargo));
        }
        if (is_array($orderInfo)) {
            $request->setOrderInfo($this->convertToStringKeyedArray($orderInfo));
        }
    }

    /**
     * 设置可选参数
     *
     * @param array<string, mixed> $params
     */
    private function setOptionalParameters(PreAddOrderRequest $request, array $params): void
    {
        if (isset($params['shop_no']) && is_string($params['shop_no'])) {
            $request->setShopNo($params['shop_no']);
        }

        if (isset($params['shop']) && is_array($params['shop'])) {
            $request->setShop($this->convertToStringKeyedArray($params['shop']));
        }
    }

    /**
     * 转换为字符串键值的数组
     *
     * @param array<mixed, mixed> $array
     * @return array<string, mixed>
     */
    private function convertToStringKeyedArray(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $result[(string) $key] = $value;
        }

        return $result;
    }

    /**
     * 下单（真实创建订单）
     *
     * @return array<string, mixed>
     */
    public function addOrder(Order $order): array
    {
        try {
            $requestParams = $order->toRequestArray();
            $order->setRequestData($requestParams);

            $request = new AddOrderRequest();

            $shopId = $requestParams['shopid'] ?? null;
            $deliveryId = $requestParams['delivery_id'] ?? null;
            $shopOrderId = $requestParams['shop_order_id'] ?? null;

            if (is_string($shopId)) {
                $request->setShopId($shopId);
            }
            if (is_string($deliveryId)) {
                $request->setDeliveryId($deliveryId);
            }
            if (is_string($shopOrderId)) {
                $request->setShopOrderId($shopOrderId);
            }

            $request->setSender($order->getSenderInfo());
            $request->setReceiver($order->getReceiverInfo());
            $request->setCargo($order->getCargoInfo());
            $request->setOrderInfo($order->getOrderInfo());

            if (isset($requestParams['shop_no']) && is_string($requestParams['shop_no'])) {
                $request->setShopNo($requestParams['shop_no']);
            }

            $request->setShop($order->getShopInfo());

            $response = $this->client->request($request);
            if (is_array($response)) {
                $order->setResponseData($response);
                $order->updateFromResponse($response);
            }
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            return is_array($response) ? $response : [];
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
     *
     * @return array<string, mixed>
     */
    public function preCancelOrder(string $wechatOrderId): array
    {
        try {
            $order = $this->orderRepository->findByWechatOrderId($wechatOrderId);
            if (null === $order) {
                throw new DeliveryException('订单不存在');
            }

            $params = [
                'order_id' => $wechatOrderId,
                'delivery_id' => $order->getDeliveryCompanyId(),
                'shop_id' => $order->getBindAccountId(),
            ];
            $order->setRequestData($params);

            $deliveryId = $order->getDeliveryCompanyId();
            $shopId = $order->getBindAccountId();

            if (null === $deliveryId || null === $shopId) {
                throw new DeliveryException('订单配送公司ID或商家ID不能为空');
            }

            $request = new PreCancelOrderRequest();
            $request->setOrderId($wechatOrderId);
            $request->setDeliveryId($deliveryId);
            $request->setShopId($shopId);

            $response = $this->client->request($request);
            if (is_array($response)) {
                $order->setResponseData($response);
            }
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            return is_array($response) ? $response : [];
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
     *
     * @return array<string, mixed>
     */
    public function cancelOrder(string $wechatOrderId, string $reason = ''): array
    {
        try {
            $order = $this->orderRepository->findByWechatOrderId($wechatOrderId);
            if (null === $order) {
                throw new DeliveryException('订单不存在');
            }

            $params = [
                'order_id' => $wechatOrderId,
                'delivery_id' => $order->getDeliveryCompanyId(),
                'shop_id' => $order->getBindAccountId(),
                'cancel_reason_id' => 0,
                'cancel_reason' => '' !== $reason ? $reason : '商家取消',
            ];
            $order->setRequestData($params);

            $deliveryId = $order->getDeliveryCompanyId();
            $shopId = $order->getBindAccountId();

            if (null === $deliveryId || null === $shopId) {
                throw new DeliveryException('订单配送公司ID或商家ID不能为空');
            }

            $request = new CancelOrderRequest();
            $request->setOrderId($wechatOrderId);
            $request->setDeliveryId($deliveryId);
            $request->setShopId($shopId);
            $request->setCancelReasonId(0);
            $request->setCancelReason('' !== $reason ? $reason : '商家取消');

            $response = $this->client->request($request);
            if (is_array($response)) {
                $order->setResponseData($response);
            }
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            return is_array($response) ? $response : [];
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
     *
     * @return array<string, mixed>
     */
    public function reOrder(string $wechatOrderId): array
    {
        try {
            $order = $this->orderRepository->findByWechatOrderId($wechatOrderId);
            if (null === $order) {
                throw new DeliveryException('订单不存在');
            }

            $params = [
                'order_id' => $wechatOrderId,
                'delivery_id' => $order->getDeliveryCompanyId(),
                'shop_id' => $order->getBindAccountId(),
            ];
            $order->setRequestData($params);

            $deliveryId = $order->getDeliveryCompanyId();
            $shopId = $order->getBindAccountId();

            if (null === $deliveryId || null === $shopId) {
                throw new DeliveryException('订单配送公司ID或商家ID不能为空');
            }

            $request = new ReOrderRequest();
            $request->setOrderId($wechatOrderId);
            $request->setDeliveryId($deliveryId);
            $request->setShopId($shopId);

            $response = $this->client->request($request);
            if (is_array($response)) {
                $order->setResponseData($response);
            }
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            return is_array($response) ? $response : [];
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
     *
     * @return array<string, mixed>
     */
    public function getOrder(string $wechatOrderId): array
    {
        try {
            $order = $this->orderRepository->findByWechatOrderId($wechatOrderId);
            if (null === $order) {
                throw new DeliveryException('订单不存在');
            }

            $params = [
                'order_id' => $wechatOrderId,
                'delivery_id' => $order->getDeliveryCompanyId(),
                'shop_id' => $order->getBindAccountId(),
            ];
            $order->setRequestData($params);

            $deliveryId = $order->getDeliveryCompanyId();
            $shopId = $order->getBindAccountId();

            if (null === $deliveryId || null === $shopId) {
                throw new DeliveryException('订单配送公司ID或商家ID不能为空');
            }

            $request = new GetOrderRequest();
            $request->setOrderId($wechatOrderId);
            $request->setDeliveryId($deliveryId);
            $request->setShopId($shopId);

            $response = $this->client->request($request);
            if (is_array($response)) {
                $order->setResponseData($response);
                $order->updateFromResponse($response);
            }
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            return is_array($response) ? $response : [];
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
     * @return array<string, mixed>
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
            $request->setShopId($shopid);
            $request->setShopOrderId($shop_order_id);
            $request->setWaybillId($waybill_id);
            $request->setTips($tips);
            $request->setDeliverySign($delivery_sign);

            if (null !== $shop_no) {
                $request->setShopNo($shop_no);
            }

            if (null !== $remark) {
                $request->setRemark($remark);
            }

            $response = $this->client->request($request);

            return is_array($response) ? $response : [];
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
