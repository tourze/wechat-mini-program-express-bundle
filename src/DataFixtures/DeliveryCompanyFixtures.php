<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatMiniProgramExpressBundle\Entity\DeliveryCompany;

#[When(env: 'test')]
class DeliveryCompanyFixtures extends Fixture
{
    public const DELIVERY_COMPANY_SF_REFERENCE = 'delivery-company-sf';
    public const DELIVERY_COMPANY_EMS_REFERENCE = 'delivery-company-ems';
    public const DELIVERY_COMPANY_YUANTONG_REFERENCE = 'delivery-company-yuantong';
    public const DELIVERY_COMPANY_ZHONGTONG_REFERENCE = 'delivery-company-zhongtong';

    public function load(ObjectManager $manager): void
    {
        $deliveryCompany1 = new DeliveryCompany();
        $deliveryCompany1->setDeliveryId('SF');
        $deliveryCompany1->setDeliveryName('顺丰同城急送');
        $deliveryCompany1->setValid(true);
        $deliveryCompany1->setCreatedBy('1');

        $deliveryCompany2 = new DeliveryCompany();
        $deliveryCompany2->setDeliveryId('EMS');
        $deliveryCompany2->setDeliveryName('邮政EMS');
        $deliveryCompany2->setValid(true);
        $deliveryCompany2->setCreatedBy('1');

        $deliveryCompany3 = new DeliveryCompany();
        $deliveryCompany3->setDeliveryId('YUANTONG');
        $deliveryCompany3->setDeliveryName('圆通速递');
        $deliveryCompany3->setValid(false);
        $deliveryCompany3->setCreatedBy('1');

        $deliveryCompany4 = new DeliveryCompany();
        $deliveryCompany4->setDeliveryId('ZHONGTONG');
        $deliveryCompany4->setDeliveryName('中通快递');
        $deliveryCompany4->setValid(true);
        $deliveryCompany4->setCreatedBy('1');

        $manager->persist($deliveryCompany1);
        $manager->persist($deliveryCompany2);
        $manager->persist($deliveryCompany3);
        $manager->persist($deliveryCompany4);

        $this->addReference(self::DELIVERY_COMPANY_SF_REFERENCE, $deliveryCompany1);
        $this->addReference(self::DELIVERY_COMPANY_EMS_REFERENCE, $deliveryCompany2);
        $this->addReference(self::DELIVERY_COMPANY_YUANTONG_REFERENCE, $deliveryCompany3);
        $this->addReference(self::DELIVERY_COMPANY_ZHONGTONG_REFERENCE, $deliveryCompany4);

        $manager->flush();
    }
}
