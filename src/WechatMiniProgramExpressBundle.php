<?php

namespace WechatMiniProgramExpressBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;

#[AsPermission(title: '即时配送')]
class WechatMiniProgramExpressBundle extends Bundle implements BundleDependencyInterface
{
    public static function getBundleDependencies(): array
    {
        return [
            \WechatMiniProgramBundle\WechatMiniProgramBundle::class => ['all' => true],
        ];
    }
}
