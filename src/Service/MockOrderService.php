<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramExpressBundle\Exception\DeliveryException;
use WechatMiniProgramExpressBundle\Repository\OrderRepository;
use WechatMiniProgramExpressBundle\Request\MockUpdateOrderRequest;
use WechatMiniProgramExpressBundle\Request\RealMockUpdateOrderRequest;

/**
 * 微信小程序即时配送模拟订单服务
 *
 * 负责测试环境下模拟订单状态变更操作
 */
#[Autoconfigure(public: true)]
#[WithMonologChannel(channel: 'wechat_mini_program_express')]
readonly class MockOrderService
{
    public function __construct(
        private Client $client,
        private OrderRepository $orderRepository,
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * 模拟更新配送单状态
     *
     * 仅限商家完成开发后，在测试环境验证时使用。
     * 该接口只能用于沙盒环境开发测试，接入商户上线前必须去掉。
     *
     * @param string $orderId    配送单ID
     * @param string $actionType 动作类型（OnAccept, OrderPickup, OrderDelivery, OrderCancel, OrderException, OrderReturn）
     * @param string|null $mockInfo 附加信息
     *
     * @return array<string, mixed>
     *
     * @throws DeliveryException
     */
    public function mockUpdateOrder(string $orderId, string $actionType, ?string $mockInfo = null): array
    {
        try {
            $order = $this->orderRepository->findByWechatOrderId($orderId);
            if (null === $order) {
                throw new DeliveryException('订单不存在');
            }

            $deliveryId = $order->getDeliveryCompanyId();
            $shopId = $order->getBindAccountId();

            if (null === $deliveryId || null === $shopId) {
                throw new DeliveryException('订单配送公司ID或商家ID不能为空');
            }

            $params = [
                'order_id' => $orderId,
                'delivery_id' => $deliveryId,
                'shop_id' => $shopId,
                'action_type' => $actionType,
            ];

            if (null !== $mockInfo) {
                $params['mock_info'] = $mockInfo;
            }

            $order->setRequestData($params);

            $request = new MockUpdateOrderRequest();
            $request->setOrderId($orderId);
            $request->setDeliveryId($deliveryId);
            $request->setShopId($shopId);
            $request->setActionType($actionType);

            if (null !== $mockInfo) {
                $request->setMockInfo($mockInfo);
            }

            $response = $this->client->request($request);
            if (is_array($response)) {
                $order->setResponseData($response);
            }
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            return is_array($response) ? $response : [];
        } catch (\Throwable $e) {
            $this->logger->error('模拟更新配送单状态失败', [
                'exception' => $e->getMessage(),
                'order_id' => $orderId,
                'action_type' => $actionType,
            ]);
            throw new DeliveryException('模拟更新配送单状态失败: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 模拟配送公司更新配送单状态
     *
     * 该接口用于模拟配送公司更新配送单状态，可进行测试账户下的单，
     * 将请求转发到运力测试环境。目前支持顺丰同城和达达。
     *
     * @param string $shopId       商家ID
     * @param string $shopOrderId  商家订单号
     * @param int    $orderStatus  配送状态
     * @param int    $actionTime   状态变更时间点，Unix秒级时间戳
     * @param string $deliverySign 配送公司校验串
     * @param string|null $actionMsg 附加信息
     *
     * @return array<string, mixed>
     *
     * @throws DeliveryException
     */
    public function realMockUpdateOrder(
        string $shopId,
        string $shopOrderId,
        int $orderStatus,
        int $actionTime,
        string $deliverySign,
        ?string $actionMsg = null,
    ): array {
        try {
            $order = $this->orderRepository->findByStoreOrderId($shopOrderId);
            if (null === $order) {
                throw new DeliveryException('订单不存在');
            }

            $params = [
                'shopid' => $shopId,
                'shop_order_id' => $shopOrderId,
                'order_status' => $orderStatus,
                'action_time' => $actionTime,
                'delivery_sign' => $deliverySign,
            ];

            if (null !== $actionMsg) {
                $params['action_msg'] = $actionMsg;
            }

            $order->setRequestData($params);

            $request = new RealMockUpdateOrderRequest();
            $request->setShopId($shopId);
            $request->setShopOrderId($shopOrderId);
            $request->setOrderStatus($orderStatus);
            $request->setActionTime($actionTime);
            $request->setDeliverySign($deliverySign);

            if (null !== $actionMsg) {
                $request->setActionMsg($actionMsg);
            }

            $response = $this->client->request($request);
            if (is_array($response)) {
                $order->setResponseData($response);
            }
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            return is_array($response) ? $response : [];
        } catch (\Throwable $e) {
            $this->logger->error('模拟配送公司更新配送单状态失败', [
                'exception' => $e->getMessage(),
                'shop_id' => $shopId,
                'shop_order_id' => $shopOrderId,
                'order_status' => $orderStatus,
            ]);
            throw new DeliveryException('模拟配送公司更新配送单状态失败: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }
}
