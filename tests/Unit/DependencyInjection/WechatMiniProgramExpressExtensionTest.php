<?php

namespace WechatMiniProgramExpressBundle\Tests\Unit\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use WechatMiniProgramExpressBundle\DependencyInjection\WechatMiniProgramExpressExtension;

class WechatMiniProgramExpressExtensionTest extends TestCase
{
    private WechatMiniProgramExpressExtension $extension;
    private ContainerBuilder $container;

    protected function setUp(): void
    {
        $this->extension = new WechatMiniProgramExpressExtension();
        $this->container = new ContainerBuilder();
    }

    public function testLoad(): void
    {
        $this->extension->load([], $this->container);
        
        $this->assertTrue($this->container->has('WechatMiniProgramExpressBundle\Service\DeliveryConfigService'));
        $this->assertTrue($this->container->has('WechatMiniProgramExpressBundle\Service\DeliveryOrderService'));
        $this->assertTrue($this->container->has('WechatMiniProgramExpressBundle\Service\MockOrderService'));
        $this->assertTrue($this->container->has('WechatMiniProgramExpressBundle\Service\OrderQueryService'));
    }

    public function testExtensionCanBeInstantiated(): void
    {
        $this->assertInstanceOf(WechatMiniProgramExpressExtension::class, $this->extension);
    }
}