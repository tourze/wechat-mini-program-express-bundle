<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatMiniProgramExpressBundle\Entity\Embed\CargoInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\OrderInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\ReceiverInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\SenderInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\ShopInfo;
use WechatMiniProgramExpressBundle\Entity\Order;

#[When(env: 'test')]
class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    public const ORDER_PENDING_REFERENCE = 'order-pending';
    public const ORDER_ACCEPTED_REFERENCE = 'order-accepted';
    public const ORDER_PICKING_REFERENCE = 'order-picking';

    public function load(ObjectManager $manager): void
    {
        $order1 = new Order();
        $order1->setWechatOrderId('WX_ORDER_001');
        $order1->setDeliveryId('SF');
        $order1->setStatus('PENDING');
        $order1->setFee('8.50');
        $order1->setDeliveryCompanyId('SF');
        $order1->setBindAccountId('BIND_001');

        $senderInfo1 = new SenderInfo();
        $senderInfo1->setName('测试发件人');
        $senderInfo1->setCity('北京市');
        $senderInfo1->setAddress('朝阳区中关村科技园区123号');
        $senderInfo1->setPhone('13800138001');
        $senderInfo1->setLng(116.3974);
        $senderInfo1->setLat(39.9093);

        $receiverInfo1 = new ReceiverInfo();
        $receiverInfo1->setName('测试收件人');
        $receiverInfo1->setCity('上海市');
        $receiverInfo1->setAddress('浦东新区陆家嘴金融中心456号');
        $receiverInfo1->setPhone('13900139001');
        $receiverInfo1->setLng(121.4944);
        $receiverInfo1->setLat(31.2397);

        $cargoInfo1 = new CargoInfo();
        $cargoInfo1->setGoodsValue(50.00);
        $cargoInfo1->setGoodsHeight(10.0);
        $cargoInfo1->setGoodsLength(20.0);
        $cargoInfo1->setGoodsWidth(15.0);
        $cargoInfo1->setGoodsWeight(1.5);
        $cargoInfo1->setGoodsDetail('测试商品详情');
        $cargoInfo1->setGoodsCount(2);

        $orderInfo1 = new OrderInfo();
        $orderInfo1->setOrderTime(time());
        $orderInfo1->setPoiSeq('POI_001');
        $orderInfo1->setNote('请小心轻放');
        $orderInfo1->setExpectedDeliveryTime(time() + 3600);

        $shopInfo1 = new ShopInfo();
        $shopInfo1->setGoodsName('测试商品');
        $shopInfo1->setGoodsCount(2);
        $shopInfo1->setDeliverySign('TEST_DELIVERY');

        $order1->setSenderInfo($senderInfo1);
        $order1->setReceiverInfo($receiverInfo1);
        $order1->setCargoInfo($cargoInfo1);
        $order1->setOrderInfo($orderInfo1);
        $order1->setShopInfo($shopInfo1);

        $order2 = new Order();
        $order2->setWechatOrderId('WX_ORDER_002');
        $order2->setDeliveryId('EMS');
        $order2->setStatus('ACCEPTED');
        $order2->setFee('12.00');
        $order2->setDeliveryCompanyId('EMS');
        $order2->setBindAccountId('BIND_002');

        $senderInfo2 = new SenderInfo();
        $senderInfo2->setName('测试发件人2');
        $senderInfo2->setCity('广州市');
        $senderInfo2->setAddress('天河区珠江新城商务大厦789号');
        $senderInfo2->setPhone('13700137002');
        $senderInfo2->setLng(113.3265);
        $senderInfo2->setLat(23.1291);

        $receiverInfo2 = new ReceiverInfo();
        $receiverInfo2->setName('测试收件人2');
        $receiverInfo2->setCity('深圳市');
        $receiverInfo2->setAddress('南山区科技园高新大道101号');
        $receiverInfo2->setPhone('13600136002');
        $receiverInfo2->setLng(113.9547);
        $receiverInfo2->setLat(22.5406);

        $cargoInfo2 = new CargoInfo();
        $cargoInfo2->setGoodsValue(120.00);
        $cargoInfo2->setGoodsHeight(5.0);
        $cargoInfo2->setGoodsLength(30.0);
        $cargoInfo2->setGoodsWidth(20.0);
        $cargoInfo2->setGoodsWeight(2.0);
        $cargoInfo2->setGoodsDetail('电子产品');
        $cargoInfo2->setGoodsCount(1);

        $orderInfo2 = new OrderInfo();
        $orderInfo2->setOrderTime(time());
        $orderInfo2->setPoiSeq('POI_002');
        $orderInfo2->setNote('加急配送');
        $orderInfo2->setExpectedDeliveryTime(time() + 1800);

        $shopInfo2 = new ShopInfo();
        $shopInfo2->setGoodsName('电子产品');
        $shopInfo2->setGoodsCount(1);
        $shopInfo2->setDeliverySign('URGENT_DELIVERY');

        $order2->setSenderInfo($senderInfo2);
        $order2->setReceiverInfo($receiverInfo2);
        $order2->setCargoInfo($cargoInfo2);
        $order2->setOrderInfo($orderInfo2);
        $order2->setShopInfo($shopInfo2);

        $manager->persist($order1);
        $manager->persist($order2);

        $this->addReference(self::ORDER_PENDING_REFERENCE, $order1);
        $this->addReference(self::ORDER_ACCEPTED_REFERENCE, $order2);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DeliveryCompanyFixtures::class,
            BindAccountFixtures::class,
        ];
    }
}
