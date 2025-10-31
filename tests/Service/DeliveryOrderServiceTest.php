<?php

namespace WechatMiniProgramExpressBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramExpressBundle\Entity\Embed\CargoInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\OrderInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\ReceiverInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\SenderInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\ShopInfo;
use WechatMiniProgramExpressBundle\Entity\Order;
use WechatMiniProgramExpressBundle\Exception\DeliveryException;
use WechatMiniProgramExpressBundle\Service\DeliveryOrderService;

/**
 * @internal
 */
#[CoversClass(DeliveryOrderService::class)]
#[RunTestsInSeparateProcesses]
final class DeliveryOrderServiceTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
        // 空实现，子类可以根据需要扩展
    }

    public function testPreAddOrderWithValidOrderThrowsException(): void
    {
        $service = self::getService(DeliveryOrderService::class);
        $order = $this->createValidOrder();

        $this->expectException(DeliveryException::class);
        $service->preAddOrder($order);
    }

    public function testAddOrderWithValidOrderThrowsException(): void
    {
        $service = self::getService(DeliveryOrderService::class);
        $order = $this->createValidOrder();

        $this->expectException(DeliveryException::class);
        $service->addOrder($order);
    }

    public function testPreCancelOrderWithInvalidIdThrowsException(): void
    {
        $service = self::getService(DeliveryOrderService::class);

        $this->expectException(DeliveryException::class);
        $this->expectExceptionMessage('订单不存在');
        $service->preCancelOrder('invalid-order-id');
    }

    public function testCancelOrderWithInvalidIdThrowsException(): void
    {
        $service = self::getService(DeliveryOrderService::class);

        $this->expectException(DeliveryException::class);
        $this->expectExceptionMessage('订单不存在');
        $service->cancelOrder('invalid-order-id');
    }

    public function testReOrderWithInvalidIdThrowsException(): void
    {
        $service = self::getService(DeliveryOrderService::class);

        $this->expectException(DeliveryException::class);
        $this->expectExceptionMessage('订单不存在');
        $service->reOrder('invalid-order-id');
    }

    public function testGetOrderWithInvalidIdThrowsException(): void
    {
        $service = self::getService(DeliveryOrderService::class);

        $this->expectException(DeliveryException::class);
        $this->expectExceptionMessage('订单不存在');
        $service->getOrder('invalid-order-id');
    }

    public function testAddTipsWithValidParametersThrowsException(): void
    {
        $service = self::getService(DeliveryOrderService::class);

        // 使用匿名类代替Mock，遵循静态分析规范
        $account = $this->createTestAccount();

        // 期望由于客户端请求失败而抛出异常
        $this->expectException(\Throwable::class);
        $service->addTips(
            $account,
            'test-shop-id',
            'test-shop-order-id',
            'test-waybill-id',
            10.50,
            'test-delivery-sign',
            'test-shop-no',
            'test remark'
        );
    }

    public function testAddTipsWithoutOptionalParametersThrowsException(): void
    {
        $service = self::getService(DeliveryOrderService::class);

        $account = $this->createTestAccount();

        // 期望由于客户端请求失败而抛出异常
        $this->expectException(\Throwable::class);
        $service->addTips(
            $account,
            'test-shop-id',
            'test-shop-order-id',
            'test-waybill-id',
            5.00,
            'test-delivery-sign'
        );
    }

    private function createValidOrder(): Order
    {
        $order = new Order();
        $order->setBindAccountId('shop123');
        $order->setDeliveryCompanyId('delivery123');

        // 创建发送人信息
        $senderInfo = new SenderInfo();
        $senderInfo->setName('发送人');
        $senderInfo->setCity('北京市');
        $senderInfo->setAddress('北京市朝阳区');
        $senderInfo->setPhone('13800138000');
        $senderInfo->setLng(116.4074);
        $senderInfo->setLat(39.9042);
        $order->setSenderInfo($senderInfo);

        // 创建接收人信息
        $receiverInfo = new ReceiverInfo();
        $receiverInfo->setName('接收人');
        $receiverInfo->setCity('北京市');
        $receiverInfo->setAddress('北京市海淀区');
        $receiverInfo->setPhone('13800138001');
        $receiverInfo->setLng(116.3074);
        $receiverInfo->setLat(39.9742);
        $order->setReceiverInfo($receiverInfo);

        // 创建货物信息
        $cargoInfo = new CargoInfo();
        $cargoInfo->setGoodsValue(100);
        $cargoInfo->setGoodsHeight(10);
        $cargoInfo->setGoodsLength(20);
        $cargoInfo->setGoodsWidth(15);
        $cargoInfo->setGoodsWeight(2);
        $cargoInfo->setGoodsDetail('测试商品详情');
        $cargoInfo->setCargoFirstClass('食品');
        $cargoInfo->setCargoSecondClass('饮料');
        $order->setCargoInfo($cargoInfo);

        // 创建订单信息
        $orderInfo = new OrderInfo();
        $orderInfo->setDeliveryServiceCode('4011');
        $orderInfo->setOrderType(0);
        $orderInfo->setExpectedDeliveryTime(time() + 3600);
        $orderInfo->setPoiSeq('poi123');
        $orderInfo->setNote('测试订单');
        $orderInfo->setOrderTime(time());
        $orderInfo->setIsInsured(false);
        $orderInfo->setTips(0);
        $orderInfo->setIsDirectDelivery(false);
        $orderInfo->setIsFinishCodeNeeded(false);
        $orderInfo->setIsPickupCodeNeeded(false);
        $order->setOrderInfo($orderInfo);

        // 创建商店信息
        $shopInfo = new ShopInfo();
        $shopInfo->setWxaPath('pages/index');
        $shopInfo->setImgUrl('https://example.com/image.jpg');
        $shopInfo->setGoodsName('测试商品');
        $shopInfo->setGoodsCount(1);
        $order->setShopInfo($shopInfo);

        return $order;
    }

    private function createTestAccount(): Account
    {
        // 使用匿名类代替Mock，遵循静态分析规范
        return new class extends Account {
            public function getId(): int
            {
                return 1;
            }

            public function getAppId(): string
            {
                return 'test-app-id';
            }

            public function getAppSecret(): string
            {
                return 'test-app-secret';
            }

            public function getName(): string
            {
                return 'Test Account';
            }

            public function isValid(): ?bool
            {
                return true;
            }
        };
    }
}
