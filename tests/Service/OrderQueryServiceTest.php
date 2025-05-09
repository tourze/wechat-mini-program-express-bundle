<?php

namespace WechatMiniProgramExpressBundle\Tests\Service;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramExpressBundle\Entity\Embed\OrderInfo;
use WechatMiniProgramExpressBundle\Entity\Order;
use WechatMiniProgramExpressBundle\Exception\DeliveryException;
use WechatMiniProgramExpressBundle\Repository\OrderRepository;
use WechatMiniProgramExpressBundle\Service\OrderQueryService;

class OrderQueryServiceTest extends TestCase
{
    private Client $client;
    private EntityManagerInterface $entityManager;
    private OrderRepository $orderRepository;
    private LoggerInterface $logger;
    private OrderQueryService $service;

    protected function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->orderRepository = $this->createMock(OrderRepository::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->service = new OrderQueryService(
            $this->client,
            $this->orderRepository,
            $this->entityManager,
            $this->logger
        );
    }

    public function testGetOrder_Success(): void
    {
        // 准备测试数据
        $wechatOrderId = 'wx-order-123';
        $order = $this->createOrder();
        $order->setWechatOrderId($wechatOrderId);
        $order->setDeliveryCompanyId('delivery1');
        $order->setBindAccountId('shop1');

        $responseData = [
            'errcode' => 0,
            'errmsg' => 'ok',
            'order_status' => 10,
            'rider_name' => '测试骑手',
            'rider_phone' => '13800138000',
            'delivery_sign' => 'sign1',
        ];

        // 配置模拟行为
        $this->orderRepository->expects($this->once())
            ->method('findByWechatOrderId')
            ->with($wechatOrderId)
            ->willReturn($order);

        $this->client->expects($this->once())
            ->method('request')
            ->willReturn($responseData);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($order);
        $this->entityManager->expects($this->once())
            ->method('flush');

        // 执行测试
        $result = $this->service->getOrder($wechatOrderId);

        // 断言结果
        $this->assertSame($responseData, $result);
        $this->assertSame($responseData, $order->getResponseData());
    }

    public function testGetOrder_OrderNotFound(): void
    {
        // 准备测试数据
        $wechatOrderId = 'wx-order-123';

        // 配置模拟行为：订单不存在
        $this->orderRepository->expects($this->once())
            ->method('findByWechatOrderId')
            ->with($wechatOrderId)
            ->willReturn(null);

        $this->expectException(DeliveryException::class);
        $this->expectExceptionMessage('订单不存在');

        // 执行测试
        $this->service->getOrder($wechatOrderId);
    }

    public function testGetOrder_Error(): void
    {
        // 准备测试数据
        $wechatOrderId = 'wx-order-123';
        $order = $this->createOrder();
        $order->setWechatOrderId($wechatOrderId);
        $order->setDeliveryCompanyId('delivery1');
        $order->setBindAccountId('shop1');

        // 配置模拟行为：API抛出异常
        $this->orderRepository->expects($this->once())
            ->method('findByWechatOrderId')
            ->with($wechatOrderId)
            ->willReturn($order);

        $this->client->expects($this->once())
            ->method('request')
            ->willThrowException(new \Exception('API错误'));

        $this->logger->expects($this->once())
            ->method('error')
            ->with(
                $this->equalTo('查询订单失败'),
                $this->callback(function ($context) use ($wechatOrderId) {
                    return isset($context['exception']) && $context['order_id'] === $wechatOrderId;
                })
            );

        $this->expectException(DeliveryException::class);
        $this->expectExceptionMessage('查询订单失败: API错误');

        // 执行测试
        $this->service->getOrder($wechatOrderId);
    }

    public function testAddTips_Success(): void
    {
        // 准备测试数据
        $wechatOrderId = 'wx-order-123';
        $tips = 5.0;
        $remark = '加小费测试';

        $order = $this->createOrder();
        $order->setWechatOrderId($wechatOrderId);
        $order->setBindAccountId('shop1');
        
        $orderInfo = new OrderInfo();
        $orderInfo->setPoiSeq('shop-order-123');
        $order->setOrderInfo($orderInfo);
        $order->setDeliveryId('delivery-id-123');

        $responseData = [
            'errcode' => 0,
            'errmsg' => 'ok',
        ];

        // 配置模拟行为
        $this->orderRepository->expects($this->once())
            ->method('findByWechatOrderId')
            ->with($wechatOrderId)
            ->willReturn($order);

        $this->client->expects($this->once())
            ->method('request')
            ->willReturn($responseData);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($order);
        $this->entityManager->expects($this->once())
            ->method('flush');

        // 执行测试
        $result = $this->service->addTips($wechatOrderId, $tips, $remark);

        // 断言结果
        $this->assertSame($responseData, $result);
        $this->assertSame($responseData, $order->getResponseData());
    }

    public function testAddTips_OrderNotFound(): void
    {
        // 准备测试数据
        $wechatOrderId = 'wx-order-123';
        $tips = 5.0;

        // 配置模拟行为：订单不存在
        $this->orderRepository->expects($this->once())
            ->method('findByWechatOrderId')
            ->with($wechatOrderId)
            ->willReturn(null);

        $this->expectException(DeliveryException::class);
        $this->expectExceptionMessage('订单不存在');

        // 执行测试
        $this->service->addTips($wechatOrderId, $tips);
    }

    public function testAddTips_Error(): void
    {
        // 准备测试数据
        $wechatOrderId = 'wx-order-123';
        $tips = 5.0;
        
        $order = $this->createOrder();
        $order->setWechatOrderId($wechatOrderId);
        $order->setBindAccountId('shop1');
        
        $orderInfo = new OrderInfo();
        $orderInfo->setPoiSeq('shop-order-123');
        $order->setOrderInfo($orderInfo);
        $order->setDeliveryId('delivery-id-123');

        // 配置模拟行为：API抛出异常
        $this->orderRepository->expects($this->once())
            ->method('findByWechatOrderId')
            ->with($wechatOrderId)
            ->willReturn($order);

        $this->client->expects($this->once())
            ->method('request')
            ->willThrowException(new \Exception('API错误'));

        $this->logger->expects($this->once())
            ->method('error')
            ->with(
                $this->equalTo('增加小费失败'),
                $this->callback(function ($context) use ($wechatOrderId, $tips) {
                    return isset($context['exception']) 
                        && $context['order_id'] === $wechatOrderId
                        && $context['tips'] === $tips;
                })
            );

        $this->expectException(DeliveryException::class);
        $this->expectExceptionMessage('增加小费失败: API错误');

        // 执行测试
        $this->service->addTips($wechatOrderId, $tips);
    }

    public function testConfirmAbnormalReturn_Success(): void
    {
        // 准备测试数据
        $wechatOrderId = 'wx-order-123';
        $waybillId = 'waybill-456';
        $remark = '异常确认测试';

        $order = $this->createOrder();
        $order->setWechatOrderId($wechatOrderId);
        $order->setBindAccountId('shop1');
        
        $orderInfo = new OrderInfo();
        $orderInfo->setPoiSeq('shop-order-123');
        $order->setOrderInfo($orderInfo);

        $responseData = [
            'errcode' => 0,
            'errmsg' => 'ok',
        ];

        // 配置模拟行为
        $this->orderRepository->expects($this->once())
            ->method('findByWechatOrderId')
            ->with($wechatOrderId)
            ->willReturn($order);

        $this->client->expects($this->once())
            ->method('request')
            ->willReturn($responseData);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($order);
        $this->entityManager->expects($this->once())
            ->method('flush');

        // 执行测试
        $result = $this->service->confirmAbnormalReturn($wechatOrderId, $waybillId, $remark);

        // 断言结果
        $this->assertSame($responseData, $result);
        $this->assertSame($responseData, $order->getResponseData());
    }

    public function testConfirmAbnormalReturn_OrderNotFound(): void
    {
        // 准备测试数据
        $wechatOrderId = 'wx-order-123';
        $waybillId = 'waybill-456';

        // 配置模拟行为：订单不存在
        $this->orderRepository->expects($this->once())
            ->method('findByWechatOrderId')
            ->with($wechatOrderId)
            ->willReturn(null);

        $this->expectException(DeliveryException::class);
        $this->expectExceptionMessage('订单不存在');

        // 执行测试
        $this->service->confirmAbnormalReturn($wechatOrderId, $waybillId);
    }

    public function testConfirmAbnormalReturn_Error(): void
    {
        // 准备测试数据
        $wechatOrderId = 'wx-order-123';
        $waybillId = 'waybill-456';
        
        $order = $this->createOrder();
        $order->setWechatOrderId($wechatOrderId);
        $order->setBindAccountId('shop1');
        
        $orderInfo = new OrderInfo();
        $orderInfo->setPoiSeq('shop-order-123');
        $order->setOrderInfo($orderInfo);

        // 配置模拟行为：API抛出异常
        $this->orderRepository->expects($this->once())
            ->method('findByWechatOrderId')
            ->with($wechatOrderId)
            ->willReturn($order);

        $this->client->expects($this->once())
            ->method('request')
            ->willThrowException(new \Exception('API错误'));

        $this->logger->expects($this->once())
            ->method('error');

        $this->expectException(DeliveryException::class);

        // 执行测试
        $this->service->confirmAbnormalReturn($wechatOrderId, $waybillId);
    }

    private function createOrder(): Order
    {
        return new Order();
    }
} 