<?php

namespace WechatMiniProgramExpressBundle\Tests\Entity\Embed;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Entity\Embed\ShopInfo;

/**
 * @internal
 */
#[CoversClass(ShopInfo::class)]
final class ShopInfoTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $shopInfo = new ShopInfo();

        $shopInfo->setGoodsName('测试商品');
        $this->assertSame('测试商品', $shopInfo->getGoodsName());

        $shopInfo->setGoodsCount(5);
        $this->assertSame(5, $shopInfo->getGoodsCount());

        $shopInfo->setImgUrl('https://example.com/image.jpg');
        $this->assertSame('https://example.com/image.jpg', $shopInfo->getImgUrl());

        $shopInfo->setWxaPath('pages/index/index');
        $this->assertSame('pages/index/index', $shopInfo->getWxaPath());

        $shopInfo->setWcPoi('wcpoi123');
        $this->assertSame('wcpoi123', $shopInfo->getWcPoi());

        $shopInfo->setShopOrderId('shoporder123');
        $this->assertSame('shoporder123', $shopInfo->getShopOrderId());

        $shopInfo->setDeliverySign('sign123');
        $this->assertSame('sign123', $shopInfo->getDeliverySign());
    }

    public function testSetWechatAppIdCompatibility(): void
    {
        $appId = 'wxapp123456';

        $shopInfo = new ShopInfo();
        $shopInfo->setWechatAppId($appId);

        $this->assertSame($appId, $shopInfo->getWxaPath());
    }

    public function testToRequestArray(): void
    {
        $shopInfo = new ShopInfo();
        $shopInfo->setGoodsName('测试商品');
        $shopInfo->setGoodsCount(5);
        $shopInfo->setImgUrl('https://example.com/image.jpg');
        $shopInfo->setWxaPath('pages/index/index');
        $shopInfo->setWcPoi('wcpoi123');
        $shopInfo->setShopOrderId('shoporder123');
        $shopInfo->setDeliverySign('sign123');

        $array = $shopInfo->toRequestArray();

        $this->assertSame('测试商品', $array['goods_name']);
        $this->assertSame(5, $array['goods_count']);
        $this->assertSame('https://example.com/image.jpg', $array['img_url']);
        $this->assertSame('pages/index/index', $array['wxa_path']);
        $this->assertSame('wcpoi123', $array['wc_poi']);
        $this->assertSame('shoporder123', $array['shop_order_id']);
        $this->assertSame('sign123', $array['delivery_sign']);
    }

    public function testToRequestArrayWithoutDeliverySign(): void
    {
        $shopInfo = new ShopInfo();
        $shopInfo->setGoodsName('测试商品');
        $shopInfo->setGoodsCount(5);
        // deliverySign为null

        $array = $shopInfo->toRequestArray();

        $this->assertArrayHasKey('goods_name', $array);
        $this->assertArrayHasKey('goods_count', $array);
        $this->assertArrayNotHasKey('delivery_sign', $array);
    }

    public function testToRequestArrayFiltersNullValues(): void
    {
        $shopInfo = new ShopInfo();
        $shopInfo->setGoodsName('测试商品');
        // 其他值保持null

        $array = $shopInfo->toRequestArray();

        $this->assertArrayHasKey('goods_name', $array);
        $this->assertArrayNotHasKey('goods_count', $array);
        $this->assertArrayNotHasKey('img_url', $array);
        $this->assertArrayNotHasKey('wxa_path', $array);
        $this->assertArrayNotHasKey('delivery_sign', $array);
    }

    public function testFromArray(): void
    {
        $data = [
            'goods_name' => '测试商品',
            'goods_count' => 5,
            'img_url' => 'https://example.com/image.jpg',
            'wxa_path' => 'pages/index/index',
            'wc_poi' => 'wcpoi123',
            'shop_order_id' => 'shoporder123',
            'delivery_sign' => 'sign123',
        ];

        $shopInfo = ShopInfo::fromArray($data);

        $this->assertSame('测试商品', $shopInfo->getGoodsName());
        $this->assertSame(5, $shopInfo->getGoodsCount());
        $this->assertSame('https://example.com/image.jpg', $shopInfo->getImgUrl());
        $this->assertSame('pages/index/index', $shopInfo->getWxaPath());
        $this->assertSame('wcpoi123', $shopInfo->getWcPoi());
        $this->assertSame('shoporder123', $shopInfo->getShopOrderId());
        $this->assertSame('sign123', $shopInfo->getDeliverySign());
    }

    public function testFromArrayWithPartialData(): void
    {
        $data = [
            'goods_name' => '测试商品',
            'goods_count' => 5,
        ];

        $shopInfo = ShopInfo::fromArray($data);

        $this->assertSame('测试商品', $shopInfo->getGoodsName());
        $this->assertSame(5, $shopInfo->getGoodsCount());
        $this->assertNull($shopInfo->getImgUrl());
        $this->assertNull($shopInfo->getWxaPath());
        $this->assertNull($shopInfo->getWcPoi());
        $this->assertNull($shopInfo->getShopOrderId());
        $this->assertNull($shopInfo->getDeliverySign());
    }

    public function testFromArrayWithEmptyData(): void
    {
        $shopInfo = ShopInfo::fromArray([]);

        $this->assertNull($shopInfo->getGoodsName());
        $this->assertNull($shopInfo->getGoodsCount());
        $this->assertNull($shopInfo->getImgUrl());
        $this->assertNull($shopInfo->getWxaPath());
        $this->assertNull($shopInfo->getWcPoi());
        $this->assertNull($shopInfo->getShopOrderId());
        $this->assertNull($shopInfo->getDeliverySign());
    }
}
