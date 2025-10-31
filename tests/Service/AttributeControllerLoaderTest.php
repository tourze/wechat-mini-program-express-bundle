<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Routing\RouteCollection;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use WechatMiniProgramExpressBundle\Service\AttributeControllerLoader;

/**
 * @internal
 */
#[CoversClass(AttributeControllerLoader::class)]
#[RunTestsInSeparateProcesses]
final class AttributeControllerLoaderTest extends AbstractIntegrationTestCase
{
    private AttributeControllerLoader $loader;

    protected function onSetUp(): void
    {
        $this->loader = self::getService(AttributeControllerLoader::class);
    }

    private function getLoader(): AttributeControllerLoader
    {
        return $this->loader;
    }

    public function testSupportsReturnsFalse(): void
    {
        $this->assertFalse($this->getLoader()->supports('resource'));
        $this->assertFalse($this->getLoader()->supports('resource', 'type'));
    }

    public function testAutoloadReturnsRouteCollection(): void
    {
        $collection = $this->getLoader()->autoload();

        $this->assertInstanceOf(RouteCollection::class, $collection);
    }

    public function testLoadCallsAutoload(): void
    {
        $collection = $this->getLoader()->load('resource');

        $this->assertInstanceOf(RouteCollection::class, $collection);
    }
}
