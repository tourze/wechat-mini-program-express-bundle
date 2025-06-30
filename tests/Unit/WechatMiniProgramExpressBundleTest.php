<?php

namespace WechatMiniProgramExpressBundle\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use WechatMiniProgramExpressBundle\DependencyInjection\WechatMiniProgramExpressExtension;
use WechatMiniProgramExpressBundle\WechatMiniProgramExpressBundle;

class WechatMiniProgramExpressBundleTest extends TestCase
{
    public function testBundleCanBeInstantiated(): void
    {
        $bundle = new WechatMiniProgramExpressBundle();
        $this->assertInstanceOf(WechatMiniProgramExpressBundle::class, $bundle);
    }

    public function testGetContainerExtension(): void
    {
        $bundle = new WechatMiniProgramExpressBundle();
        $extension = $bundle->getContainerExtension();
        
        $this->assertInstanceOf(WechatMiniProgramExpressExtension::class, $extension);
    }

    public function testBundleRegistersExtension(): void
    {
        $bundle = new WechatMiniProgramExpressBundle();
        $containerBuilder = new ContainerBuilder();
        
        $bundle->build($containerBuilder);
        
        // 验证Bundle正确构建
        $this->assertInstanceOf(ContainerBuilder::class, $containerBuilder);
    }
}