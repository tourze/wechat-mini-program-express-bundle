<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\Service;

use Knp\Menu\ItemInterface;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;
use WechatMiniProgramExpressBundle\Entity\BindAccount;
use WechatMiniProgramExpressBundle\Entity\DeliveryCompany;
use WechatMiniProgramExpressBundle\Entity\Order;

/**
 * 微信小程序快递管理菜单服务
 */
readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(
        private LinkGeneratorInterface $linkGenerator,
    ) {
    }

    public function __invoke(ItemInterface $item): void
    {
        if (null === $item->getChild('微信小程序')) {
            $item->addChild('微信小程序');
        }

        $wechatMenu = $item->getChild('微信小程序');
        if (null === $wechatMenu) {
            return;
        }

        if (null === $wechatMenu->getChild('快递配送')) {
            $wechatMenu->addChild('快递配送');
        }

        $expressMenu = $wechatMenu->getChild('快递配送');
        if (null === $expressMenu) {
            return;
        }

        // 配送订单管理菜单
        $expressMenu->addChild('配送订单')
            ->setUri($this->linkGenerator->getCurdListPage(Order::class))
            ->setAttribute('icon', 'fas fa-box')
            ->setAttribute('description', '管理微信小程序快递配送订单')
        ;

        // 绑定账户管理菜单
        $expressMenu->addChild('绑定账户')
            ->setUri($this->linkGenerator->getCurdListPage(BindAccount::class))
            ->setAttribute('icon', 'fas fa-link')
            ->setAttribute('description', '管理微信小程序快递绑定账户')
        ;

        // 配送公司管理菜单
        $expressMenu->addChild('配送公司')
            ->setUri($this->linkGenerator->getCurdListPage(DeliveryCompany::class))
            ->setAttribute('icon', 'fas fa-truck')
            ->setAttribute('description', '管理配送公司信息')
        ;
    }
}
