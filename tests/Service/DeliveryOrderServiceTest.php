<?php

namespace WechatMiniProgramExpressBundle\Tests\Service;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramExpressBundle\Entity\Embed\CargoInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\OrderInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\ReceiverInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\SenderInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\ShopInfo;
use WechatMiniProgramExpressBundle\Entity\Order;
use WechatMiniProgramExpressBundle\Exception\DeliveryException;
use WechatMiniProgramExpressBundle\Repository\OrderRepository;
use WechatMiniProgramExpressBundle\Service\DeliveryOrderService;

class DeliveryOrderServiceTest extends TestCase
{
    private Client $client;
    private EntityManagerInterface $entityManager;
    private OrderRepository $orderRepository;
    private LoggerInterface $logger;
    private DeliveryOrderService $service;

    protected function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->orderRepository = $this->createMock(OrderRepository::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->service = new DeliveryOrderService(
            $this->client,
            $this->orderRepository,
            $this->entityManager,
            $this->logger
        );
    }

    public function testPreAddOrder_Success(): void
    {
        // 准备测试数据
        $order = $this->createCompleteOrder();
        
        $responseData = [
            'errcode' => 0,
            'errmsg' => 'ok',
            'fee' => 12.5,
            'deliverfee' => 5.0,
            'tips' => 0,
            'insurancefee' => 0,
        ];

        // 配置模拟行为
        $this->client->expects($this->once())
            ->method('request')
            ->willReturn($responseData);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($order);
        $this->entityManager->expects($this->once())
            ->method('flush');

        // 执行测试
        $result = $this->service->preAddOrder($order);

        // 断言结果
        $this->assertSame($responseData, $result);
        $this->assertSame($responseData, $order->getResponseData());
    }

    public function testPreAddOrder_Error(): void
    {
        // 准备测试数据
        $order = $this->createCompleteOrder();

        // 配置模拟行为：API抛出异常
        $this->client->expects($this->once())
            ->method('request')
            ->willThrowException(new \Exception('API错误'));

        $this->logger->expects($this->once())
            ->method('error')
            ->with(
                $this->equalTo('预下单失败'),
                $this->callback(function ($context) {
                    return isset($context['exception']) && isset($context['params']);
                })
            );

        $this->expectException(DeliveryException::class);
        $this->expectExceptionMessage('预下单失败: API错误');

        // a执行测试
        $this->service->preAddOrder($order);
    }

    public function testAddOrder_Success(): void
    {
        // 准备测试数据
        $order = $this->createCompleteOrder();
        
        $responseData = [
            'errcode' => 0,
            'errmsg' => 'ok',
            'fee' => 12.5,
            'deliverfee' => 5.0,
            'tips' => 0,
            'insurancefee' => 0,
            'order_id' => 'wx-order-123',
            'delivery_id' => 'delivery-id-456',
            'waybill_id' => 'waybill-789',
        ];

        // 配置模拟行为
        $this->client->expects($this->once())
            ->method('request')
            ->willReturn($responseData);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($order);
        $this->entityManager->expects($this->once())
            ->method('flush');

        // 执行测试
        $result = $this->service->addOrder($order);

        // 断言结果
        $this->assertSame($responseData, $result);
        $this->assertSame($responseData, $order->getResponseData());
    }

    public function testAddOrder_Error(): void
    {
        // 准备测试数据
        $order = $this->createCompleteOrder();

        // 配置模拟行为：API抛出异常
        $this->client->expects($this->once())
            ->method('request')
            ->willThrowException(new \Exception('API错误'));

        $this->logger->expects($this->once())
            ->method('error')
            ->with(
                $this->equalTo('下单失败'),
                $this->callback(function ($context) {
                    return isset($context['exception']) && isset($context['params']);
                })
            );

        $this->expectException(DeliveryException::class);
        $this->expectExceptionMessage('下单失败: API错误');

        // 执行测试
        $this->service->addOrder($order);
    }

    public function testPreCancelOrder_Success(): void
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
            'deduct_fee' => 2.0,
            'desc' => '取消费用说明',
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
        $result = $this->service->preCancelOrder($wechatOrderId);

        // 断言结果
        $this->assertSame($responseData, $result);
        $this->assertSame($responseData, $order->getResponseData());
    }

    public function testPreCancelOrder_OrderNotFound(): void
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
        $this->service->preCancelOrder($wechatOrderId);
    }

    public function testPreCancelOrder_Error(): void
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
                $this->equalTo('预取消订单失败'),
                $this->callback(function ($context) use ($wechatOrderId) {
                    return isset($context['exception']) && $context['order_id'] === $wechatOrderId;
                })
            );

        $this->expectException(DeliveryException::class);
        $this->expectExceptionMessage('预取消订单失败: API错误');

        // 执行测试
        $this->service->preCancelOrder($wechatOrderId);
    }

    public function testCancelOrder_Success(): void
    {
        // 准备测试数据
        $wechatOrderId = 'wx-order-123';
        $reason = '商家主动取消';
        $order = $this->createOrder();
        $order->setWechatOrderId($wechatOrderId);
        $order->setDeliveryCompanyId('delivery1');
        $order->setBindAccountId('shop1');
        
        $responseData = [
            'errcode' => 0,
            'errmsg' => 'ok',
            'deduct_fee' => 2.0,
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
        $result = $this->service->cancelOrder($wechatOrderId, $reason);

        // 断言结果
        $this->assertSame($responseData, $result);
        $this->assertSame($responseData, $order->getResponseData());
    }

    public function testCancelOrder_OrderNotFound(): void
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
        $this->service->cancelOrder($wechatOrderId);
    }

    public function testCancelOrder_Error(): void
    {
        // 准备测试数据
        $wechatOrderId = 'wx-order-123';
        $reason = '商家主动取消';
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
                $this->equalTo('取消订单失败'),
                $this->callback(function ($context) use ($wechatOrderId) {
                    return isset($context['exception']) && $context['order_id'] === $wechatOrderId;
                })
            );

        $this->expectException(DeliveryException::class);
        $this->expectExceptionMessage('取消订单失败: API错误');

        // 执行测试
        $this->service->cancelOrder($wechatOrderId, $reason);
    }

    public function testReOrder_Success(): void
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
            'fee' => 12.5,
            'order_id' => 'wx-order-new-123',
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
        $result = $this->service->reOrder($wechatOrderId);

        // 断言结果
        $this->assertSame($responseData, $result);
        $this->assertSame($responseData, $order->getResponseData());
    }

    private function createOrder(): Order
    {
        return new Order();
    }

    private function createCompleteOrder(): Order
    {
        $order = new Order();
        
        $order->setDeliveryCompanyId('delivery1');
        $order->setBindAccountId('shop1');
        
        // 发送方信息
        $sender = new SenderInfo();
        $sender->setName('发送方姓名');
        $sender->setMobile('13800138001');
        $sender->setCompany('发送方公司');
        $sender->setAddress('发送方地址');
        $sender->setLat(39.98);
        $sender->setLng(116.35);
        $order->setSenderInfo($sender);
        
        // 接收方信息
        $receiver = new ReceiverInfo();
        $receiver->setName('接收方姓名');
        $receiver->setMobile('13800138002');
        $receiver->setAddress('接收方地址');
        $receiver->setLat(39.90);
        $receiver->setLng(116.40);
        $order->setReceiverInfo($receiver);
        
        // 货物信息
        $cargo = new CargoInfo();
        $cargo->setGoodsValue(100);
        $cargo->setGoodsHeight(10);
        $cargo->setGoodsLength(20);
        $cargo->setGoodsWidth(15);
        $cargo->setGoodsWeight(2);
        $cargo->setGoodsDetail('商品详情');
        $cargo->setGoodsCount(2);
        $cargo->setCargoFirstClass('食品');
        $cargo->setCargoSecondClass('快餐');
        $order->setCargoInfo($cargo);
        
        // 订单信息
        $orderInfo = new OrderInfo();
        $orderInfo->setDeliveryServiceCode('xxx');
        $orderInfo->setOrderType(0);
        $orderInfo->setExpectedDeliveryTime(time() + 3600);
        $orderInfo->setPoiSeq('shop-order-123');
        $orderInfo->setNote('备注');
        $order->setOrderInfo($orderInfo);
        
        // 商户信息
        $shop = new ShopInfo();
        $shop->setWechatAppId('wx123456');
        $shop->setImgUrl('http://example.com/img.jpg');
        $shop->setGoodsName('测试商品');
        $shop->setDeliverySign('sign123');
        $order->setShopInfo($shop);
        
        return $order;
    }
} 