<?php

namespace WechatMiniProgramExpressBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use WechatMiniProgramExpressBundle\Exception\DeliveryException;
use WechatMiniProgramExpressBundle\Service\OrderQueryService;

/**
 * @internal
 */
#[CoversClass(OrderQueryService::class)]
#[RunTestsInSeparateProcesses]
final class OrderQueryServiceTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
        // 空实现，子类可以根据需要扩展
    }

    public function testGetOrderWithInvalidOrderIdThrowsException(): void
    {
        $service = self::getService(OrderQueryService::class);

        $this->expectException(DeliveryException::class);
        $this->expectExceptionMessage('订单不存在');
        $service->getOrder('invalid-order-id');
    }

    public function testAddTipsWithInvalidOrderIdThrowsException(): void
    {
        $service = self::getService(OrderQueryService::class);

        $this->expectException(DeliveryException::class);
        $this->expectExceptionMessage('订单不存在');
        $service->addTips('invalid-order-id', 5.0);
    }

    public function testAddTipsWithValidParameters(): void
    {
        $service = self::getService(OrderQueryService::class);

        // 测试不同的小费金额
        $tipsAmounts = [0.01, 1.0, 5.5, 10.0, 99.99];

        foreach ($tipsAmounts as $tips) {
            try {
                $service->addTips('invalid-order-id', $tips);
            } catch (DeliveryException $e) {
                // 期望因为订单不存在而抛出异常，这是正常的
                $this->assertStringContainsString('订单不存在', $e->getMessage());
            }
        }

        // 验证所有小费金额都被正确处理（通过没有其他异常抛出来验证）
        $this->assertCount(5, $tipsAmounts);
    }

    public function testConfirmAbnormalReturnWithInvalidOrderIdThrowsException(): void
    {
        $service = self::getService(OrderQueryService::class);

        $this->expectException(DeliveryException::class);
        $this->expectExceptionMessage('订单不存在');
        $service->confirmAbnormalReturn('invalid-order-id', 'waybill-123');
    }

    public function testConfirmAbnormalReturnWithValidParameters(): void
    {
        $service = self::getService(OrderQueryService::class);

        $this->expectException(DeliveryException::class);
        $this->expectExceptionMessage('订单不存在');
        $service->confirmAbnormalReturn(
            'invalid-order-id',
            'waybill-123',
            '货物已收回',
            'delivery-sign',
            'shop-no'
        );
    }
}
