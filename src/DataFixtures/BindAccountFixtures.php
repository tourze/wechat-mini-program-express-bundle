<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramExpressBundle\Entity\BindAccount;

#[When(env: 'test')]
class BindAccountFixtures extends Fixture
{
    public const BIND_ACCOUNT_SF_REFERENCE = 'bind-account-sf';
    public const BIND_ACCOUNT_EMS_REFERENCE = 'bind-account-ems';
    public const BIND_ACCOUNT_SHUNFENG_REFERENCE = 'bind-account-shunfeng';

    public function load(ObjectManager $manager): void
    {
        $account = new Account();
        $account->setName('测试小程序');
        $account->setValid(true);
        $account->setAppId('wx_test_app_id');
        $account->setAppSecret('test_app_secret');

        $bindAccount1 = new BindAccount();
        $manager->persist($account);

        $bindAccount1->setAccount($account);
        $bindAccount1->setDeliveryId('SF');
        $bindAccount1->setDeliveryName('顺丰同城急送');
        $bindAccount1->setShopId('SF_SHOP_001');
        $bindAccount1->setShopNo('SF001');
        $bindAccount1->setAppSecret('sf_test_secret_key');
        $bindAccount1->setValid(true);
        $bindAccount1->setExtraConfig([
            'test_mode' => true,
            'callback_url' => 'https://test.example.org/callback',
        ]);
        $bindAccount1->setCreatedBy('1');

        $bindAccount2 = new BindAccount();
        $bindAccount2->setAccount($account);
        $bindAccount2->setDeliveryId('EMS');
        $bindAccount2->setDeliveryName('邮政EMS');
        $bindAccount2->setShopId('EMS_SHOP_001');
        $bindAccount2->setShopNo('EMS001');
        $bindAccount2->setAppSecret('ems_test_secret_key');
        $bindAccount2->setValid(true);
        $bindAccount2->setExtraConfig([
            'test_mode' => true,
            'service_type' => 'express',
        ]);
        $bindAccount2->setCreatedBy('1');

        $bindAccount3 = new BindAccount();
        $bindAccount3->setAccount($account);
        $bindAccount3->setDeliveryId('SHUNFENG');
        $bindAccount3->setDeliveryName('顺丰快递');
        $bindAccount3->setShopId('SHUNFENG_SHOP_001');
        $bindAccount3->setShopNo('SHUNFENG001');
        $bindAccount3->setAppSecret('shunfeng_test_secret_key');
        $bindAccount3->setValid(false);
        $bindAccount3->setExtraConfig([
            'test_mode' => true,
            'disabled_reason' => 'temporary_maintenance',
        ]);
        $bindAccount3->setCreatedBy('1');

        $manager->persist($bindAccount1);
        $manager->persist($bindAccount2);
        $manager->persist($bindAccount3);

        $this->addReference(self::BIND_ACCOUNT_SF_REFERENCE, $bindAccount1);
        $this->addReference(self::BIND_ACCOUNT_EMS_REFERENCE, $bindAccount2);
        $this->addReference(self::BIND_ACCOUNT_SHUNFENG_REFERENCE, $bindAccount3);

        $manager->flush();
    }
}
