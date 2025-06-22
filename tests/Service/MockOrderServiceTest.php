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
use WechatMiniProgramExpressBundle\Service\MockOrderService;

class MockOrderServiceTest extends TestCase
{
    private Client $client;
    private EntityManagerInterface $entityManager;
    private OrderRepository $orderRepository;
    private LoggerInterface $logger;
    private MockOrderService $service;

    protected function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->orderRepository = $this->createMock(OrderRepository::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->service = new MockOrderService(
            $this->client,
            $this->orderRepository,
            $this->entityManager,
            $this->logger
        );
    }

    public function testMockUpdateOrder_Success(): void
    {
        // 准备测试数据
        $wechatOrderId = 'wx-order-123';
        $order = $this->createOrder();
        $order->setWechatOrderId($wechatOrderId);
        $order->setDeliveryCompanyId('delivery1');
        $order->setBindAccountId('shop1');
        $order->setDeliveryId('delivery-id-123');
        
        $orderInfo = new OrderInfo();
        $orderInfo->setPoiSeq('shop-order-123');
        $order->setOrderInfo($orderInfo);
        
        $actionTime = time();
        $status = 20; // 在配送中
        
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
        $result = $this->service->mockUpdateOrder($wechatOrderId, 'OrderDelivery', 'mock info');

        // 断言结果
        $this->assertSame($responseData, $result);
        $this->assertSame($responseData, $order->getResponseData());
    }

    public function testMockUpdateOrder_OrderNotFound(): void
    {
        // 准备测试数据
        $wechatOrderId = 'wx-order-123';
        $status = 20;
        $actionTime = time();

        // 配置模拟行为：订单不存在
        $this->orderRepository->expects($this->once())
            ->method('findByWechatOrderId')
            ->with($wechatOrderId)
            ->willReturn(null);

        $this->expectException(DeliveryException::class);
        $this->expectExceptionMessage('订单不存在');

        // 执行测试
        $this->service->mockUpdateOrder($wechatOrderId, 'OrderDelivery', 'mock info');
    }

    public function testMockUpdateOrder_Error(): void
    {
        // 准备测试数据
        $wechatOrderId = 'wx-order-123';
        $order = $this->createOrder();
        $order->setWechatOrderId($wechatOrderId);
        $order->setDeliveryCompanyId('delivery1');
        $order->setBindAccountId('shop1');
        $order->setDeliveryId('delivery-id-123');
        
        $orderInfo = new OrderInfo();
        $orderInfo->setPoiSeq('shop-order-123');
        $order->setOrderInfo($orderInfo);
        
        $status = 20;
        $actionTime = time();

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
                $this->equalTo('模拟更新配送单状态失败'),
                $this->callback(function ($context) use ($wechatOrderId) {
                    return isset($context['exception']) && $context['order_id'] === $wechatOrderId;
                })
            );

        $this->expectException(DeliveryException::class);
        $this->expectExceptionMessage('模拟更新配送单状态失败: API错误');

        // 执行测试
        $this->service->mockUpdateOrder($wechatOrderId, 'OrderDelivery', 'mock info');
        
        // PHPUnit 的 expectException 已经是一个断言，不需要额外的断言
    }

    public function testRealMockUpdateOrder_Success(): void
    {
        // 准备测试数据
        $wechatOrderId = 'wx-order-123';
        $order = $this->createOrder();
        $order->setWechatOrderId($wechatOrderId);
        $order->setDeliveryCompanyId('delivery1');
        $order->setBindAccountId('shop1');
        $order->setDeliveryId('delivery-id-123');
        
        $orderInfo = new OrderInfo();
        $orderInfo->setPoiSeq('shop-order-123');
        $order->setOrderInfo($orderInfo);
        
        $status = 101; // 配送完成
        $actionTime = time();
        
        $responseData = [
            'errcode' => 0,
            'errmsg' => 'ok',
        ];

        // 配置模拟行为
        $this->orderRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['orderInfo.poiSeq' => 'shop-order-123'])
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
        $result = $this->service->realMockUpdateOrder('shop1', 'shop-order-123', $status, $actionTime, 'delivery_sign');

        // 断言结果
        $this->assertSame($responseData, $result);
        $this->assertSame($responseData, $order->getResponseData());
    }

    public function testRealMockUpdateOrder_OrderNotFound(): void
    {
        // 准备测试数据
        $status = 101;
        $actionTime = time();
        
        $responseData = [
            'errcode' => 0,
            'errmsg' => 'ok',
        ];

        // 配置模拟行为：订单不存在
        $this->orderRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['orderInfo.poiSeq' => 'shop-order-123'])
            ->willReturn(null);
            
        $this->client->expects($this->once())
            ->method('request')
            ->willReturn($responseData);
            
        // 执行测试
        $result = $this->service->realMockUpdateOrder('shop1', 'shop-order-123', $status, $actionTime, 'delivery_sign');
        
        // 断言结果 - 即使订单不存在，也会返回响应
        $this->assertSame($responseData, $result);
    }

    public function testRealMockUpdateOrder_Error(): void
    {
        // 准备测试数据
        $wechatOrderId = 'wx-order-123';
        $order = $this->createOrder();
        $order->setWechatOrderId($wechatOrderId);
        $order->setDeliveryCompanyId('delivery1');
        $order->setBindAccountId('shop1');
        $order->setDeliveryId('delivery-id-123');
        
        $orderInfo = new OrderInfo();
        $orderInfo->setPoiSeq('shop-order-123');
        $order->setOrderInfo($orderInfo);
        
        $status = 101;
        $actionTime = time();

        // 配置模拟行为：API抛出异常
        $this->orderRepository->expects($this->once())
            ->method('findOneBy')
            ->with(['orderInfo.poiSeq' => 'shop-order-123'])
            ->willReturn($order);

        $this->client->expects($this->once())
            ->method('request')
            ->willThrowException(new \Exception('API错误'));

        $this->logger->expects($this->once())
            ->method('error')
            ->with(
                $this->equalTo('模拟配送公司更新配送单状态失败'),
                $this->callback(function ($context) {
                    return isset($context['exception']) && 
                           isset($context['shopid']) && 
                           isset($context['shop_order_id']) && 
                           $context['shop_order_id'] === 'shop-order-123';
                })
            );

        $this->expectException(DeliveryException::class);
        $this->expectExceptionMessage('模拟配送公司更新配送单状态失败: API错误');

        // 执行测试
        $this->service->realMockUpdateOrder('shop1', 'shop-order-123', $status, $actionTime, 'delivery-sign', null);
        
        // PHPUnit 的 expectException 已经是一个断言，不需要额外的断言
    }

    private function createOrder(): Order
    {
        return new Order();
    }
} 