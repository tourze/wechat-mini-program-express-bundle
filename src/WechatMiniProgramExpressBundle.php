<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;
use WechatMiniProgramBundle\WechatMiniProgramBundle;

class WechatMiniProgramExpressBundle extends Bundle implements BundleDependencyInterface
{
    public static function getBundleDependencies(): array
    {
        return [
            DoctrineBundle::class => ['all' => true],
            WechatMiniProgramBundle::class => ['all' => true],
        ];
    }
}
