<?php

namespace WechatMiniProgramExpressBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use WechatMiniProgramExpressBundle\Service\DeliveryConfigService;

/**
 * @internal
 */
#[CoversClass(DeliveryConfigService::class)]
#[RunTestsInSeparateProcesses]
final class DeliveryConfigServiceTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
        // 空实现，子类可以根据需要扩展
    }

    public function testServiceCanBeInstantiated(): void
    {
        $service = self::getService(DeliveryConfigService::class);
        $this->assertInstanceOf(DeliveryConfigService::class, $service);
    }
}
