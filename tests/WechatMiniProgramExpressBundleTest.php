<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;
use WechatMiniProgramExpressBundle\WechatMiniProgramExpressBundle;

/**
 * @internal
 */
#[CoversClass(WechatMiniProgramExpressBundle::class)]
#[RunTestsInSeparateProcesses]
final class WechatMiniProgramExpressBundleTest extends AbstractBundleTestCase
{
}
