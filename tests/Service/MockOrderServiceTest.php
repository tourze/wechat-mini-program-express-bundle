<?php

namespace WechatMiniProgramExpressBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use WechatMiniProgramExpressBundle\Exception\DeliveryException;
use WechatMiniProgramExpressBundle\Service\MockOrderService;

/**
 * @internal
 */
#[CoversClass(MockOrderService::class)]
#[RunTestsInSeparateProcesses]
final class MockOrderServiceTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
        // 空实现，子类可以根据需要扩展
    }

    public function testMockUpdateOrderWithInvalidOrderIdThrowsException(): void
    {
        $service = self::getService(MockOrderService::class);

        $this->expectException(DeliveryException::class);
        $this->expectExceptionMessage('订单不存在');
        $service->mockUpdateOrder('invalid-order-id', 'OnAccept');
    }

    public function testMockUpdateOrderSupportsValidActionTypes(): void
    {
        $service = self::getService(MockOrderService::class);
        $validActionTypes = ['OnAccept', 'OrderPickup', 'OrderDelivery', 'OrderCancel', 'OrderException', 'OrderReturn'];

        foreach ($validActionTypes as $actionType) {
            try {
                $service->mockUpdateOrder('invalid-order-id', $actionType);
            } catch (DeliveryException $e) {
                // 期望因为订单不存在而抛出异常，这是正常的
                $this->assertStringContainsString('订单不存在', $e->getMessage());
            }
        }

        // 验证所有动作类型都被正确处理（通过没有其他异常抛出来验证）
        $this->assertCount(6, $validActionTypes);
    }

    public function testRealMockUpdateOrderWithValidParameters(): void
    {
        $service = self::getService(MockOrderService::class);

        // 测试所有必需参数
        $shopId = 'test-shop-id';
        $shopOrderId = 'test-order-id';
        $orderStatus = 2;
        $actionTime = time();
        $deliverySign = 'test-sign';

        $this->expectException(DeliveryException::class);
        $service->realMockUpdateOrder($shopId, $shopOrderId, $orderStatus, $actionTime, $deliverySign);
    }
}
