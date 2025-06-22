<?php

namespace WechatMiniProgramExpressBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramExpressBundle\Exception\DeliveryException;
use WechatMiniProgramExpressBundle\Repository\OrderRepository;
use WechatMiniProgramExpressBundle\Request\MockUpdateOrderRequest;
use WechatMiniProgramExpressBundle\Request\RealMockUpdateOrderRequest;

/**
 * 微信小程序即时配送订单状态模拟服务
 *
 * 负责在测试环境中模拟配送单状态更新，
 * 该服务仅用于开发和测试阶段，生产环境不应使用
 */
#[Autoconfigure(lazy: true, public: true)]
class MockOrderService
{
    public function __construct(
        private readonly Client $client,
        private readonly OrderRepository $orderRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * 模拟更新配送单状态
     *
     * 仅限测试环境使用，可以模拟实际的物流更新，使订单进入某一个状态
     *
     * @param string      $wechatOrderId 微信订单ID
     * @param string      $actionType    操作类型，可选值：OnAccept、OrderPickup、OrderDelivery、OrderCancel、OrderException、OrderReturn
     * @param string|null $mockInfo      附加信息
     *
     * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/mockUpdateOrder.html
     */
    public function mockUpdateOrder(
        string $wechatOrderId,
        string $actionType,
        ?string $mockInfo = null,
    ): array {
        try {
            $order = $this->orderRepository->findByWechatOrderId($wechatOrderId);
            if ($order === null) {
                throw new DeliveryException('订单不存在');
            }

            $request = new MockUpdateOrderRequest();
            $request->setOrderId($wechatOrderId)
                ->setDeliveryId($order->getDeliveryCompanyId())
                ->setShopId($order->getBindAccountId())
                ->setActionType($actionType);

            if (null !== $mockInfo) {
                $request->setMockInfo($mockInfo);
            }

            $requestParams = [
                'order_id' => $wechatOrderId,
                'delivery_id' => $order->getDeliveryCompanyId(),
                'shop_id' => $order->getBindAccountId(),
                'action_type' => $actionType,
            ];

            if (null !== $mockInfo) {
                $requestParams['mock_info'] = $mockInfo;
            }

            $order->setRequestData($requestParams);

            $response = $this->client->request($request);
            $order->setResponseData($response);
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            return $response;
        } catch (\Throwable $e) {
            $this->logger->error('模拟更新配送单状态失败', [
                'exception' => $e->getMessage(),
                'order_id' => $wechatOrderId,
                'action_type' => $actionType,
            ]);
            throw new DeliveryException('模拟更新配送单状态失败: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * 模拟配送公司更新配送单状态
     *
     * 该接口用于模拟配送公司更新配送单状态，可进行测试账户下的单，将请求转发到运力测试环境。
     * 该接口只能用于测试，请求会转发到运力测试环境，目前支持顺丰同城和达达。
     *
     * @param string      $shopId       商家ID，由配送公司分配的唯一ID
     * @param string      $shopOrderId  商家订单号
     * @param int         $orderStatus  配送状态
     * @param int         $actionTime   状态变更时间点，Unix秒级时间戳，如果不填则默认为当前时间
     * @param string      $deliverySign 用配送公司提供的appSecret加密的校验串
     * @param string|null $actionMsg    附加信息
     *
     * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/realMockUpdateOrder.html
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
            $request = new RealMockUpdateOrderRequest();
            $request->setShopId($shopId)
                ->setShopOrderId($shopOrderId)
                ->setOrderStatus($orderStatus)
                ->setActionTime($actionTime)
                ->setDeliverySign($deliverySign);

            if (null !== $actionMsg) {
                $request->setActionMsg($actionMsg);
            }

            $requestParams = [
                'shopid' => $shopId,
                'shop_order_id' => $shopOrderId,
                'order_status' => $orderStatus,
                'action_time' => $actionTime,
                'delivery_sign' => $deliverySign,
            ];

            if (null !== $actionMsg) {
                $requestParams['action_msg'] = $actionMsg;
            }

            // 尝试查找订单并记录请求数据
            $order = $this->orderRepository->findOneBy(['orderInfo.poiSeq' => $shopOrderId]);
            if ((bool) $order) {
                $order->setRequestData($requestParams);
            }

            $response = $this->client->request($request);

            // 如果找到了订单，记录响应数据
            if ((bool) $order) {
                $order->setResponseData($response);
                $this->entityManager->persist($order);
                $this->entityManager->flush();
            }

            return $response;
        } catch (\Throwable $e) {
            $this->logger->error('模拟配送公司更新配送单状态失败', [
                'exception' => $e->getMessage(),
                'shopid' => $shopId,
                'shop_order_id' => $shopOrderId,
                'order_status' => $orderStatus,
            ]);
            throw new DeliveryException('模拟配送公司更新配送单状态失败: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }
}
