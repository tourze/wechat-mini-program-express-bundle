<?php

namespace WechatMiniProgramExpressBundle\Tests\Unit\Entity\Embed;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Entity\Embed\ShopInfo;

class ShopInfoTest extends TestCase
{
    private ShopInfo $shopInfo;

    protected function setUp(): void
    {
        $this->shopInfo = new ShopInfo();
    }

    public function testGettersAndSetters(): void
    {
        $goodsName = '测试商品';
        $goodsCount = 5;
        $imgUrl = 'https://example.com/image.jpg';
        $wxaPath = 'pages/index/index';
        $deliverySign = 'sign123';

        $this->shopInfo->setGoodsName($goodsName);
        $this->shopInfo->setGoodsCount($goodsCount);
        $this->shopInfo->setImgUrl($imgUrl);
        $this->shopInfo->setWxaPath($wxaPath);
        $this->shopInfo->setDeliverySign($deliverySign);

        $this->assertSame($goodsName, $this->shopInfo->getGoodsName());
        $this->assertSame($goodsCount, $this->shopInfo->getGoodsCount());
        $this->assertSame($imgUrl, $this->shopInfo->getImgUrl());
        $this->assertSame($wxaPath, $this->shopInfo->getWxaPath());
        $this->assertSame($deliverySign, $this->shopInfo->getDeliverySign());
    }

    public function testSetWechatAppIdCompatibility(): void
    {
        $appId = 'wxapp123456';
        
        $result = $this->shopInfo->setWechatAppId($appId);
        
        $this->assertSame($this->shopInfo, $result);
        $this->assertSame($appId, $this->shopInfo->getWxaPath());
    }

    public function testToRequestArray(): void
    {
        $this->shopInfo->setGoodsName('测试商品');
        $this->shopInfo->setGoodsCount(5);
        $this->shopInfo->setImgUrl('https://example.com/image.jpg');
        $this->shopInfo->setWxaPath('pages/index/index');
        $this->shopInfo->setDeliverySign('sign123');

        $array = $this->shopInfo->toRequestArray();

        $this->assertSame('测试商品', $array['goods_name']);
        $this->assertSame(5, $array['goods_count']);
        $this->assertSame('https://example.com/image.jpg', $array['img_url']);
        $this->assertSame('pages/index/index', $array['wxa_path']);
        $this->assertSame('sign123', $array['delivery_sign']);
    }

    public function testToRequestArrayWithoutDeliverySign(): void
    {
        $this->shopInfo->setGoodsName('测试商品');
        $this->shopInfo->setGoodsCount(5);
        // deliverySign为null

        $array = $this->shopInfo->toRequestArray();

        $this->assertArrayHasKey('goods_name', $array);
        $this->assertArrayHasKey('goods_count', $array);
        $this->assertArrayNotHasKey('delivery_sign', $array);
    }

    public function testToRequestArrayFiltersNullValues(): void
    {
        $this->shopInfo->setGoodsName('测试商品');
        // 其他值保持null

        $array = $this->shopInfo->toRequestArray();

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
            'delivery_sign' => 'sign123',
        ];

        $shopInfo = ShopInfo::fromArray($data);

        $this->assertSame('测试商品', $shopInfo->getGoodsName());
        $this->assertSame(5, $shopInfo->getGoodsCount());
        $this->assertSame('https://example.com/image.jpg', $shopInfo->getImgUrl());
        $this->assertSame('pages/index/index', $shopInfo->getWxaPath());
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
        $this->assertNull($shopInfo->getDeliverySign());
    }

    public function testFromArrayWithEmptyData(): void
    {
        $shopInfo = ShopInfo::fromArray([]);

        $this->assertNull($shopInfo->getGoodsName());
        $this->assertNull($shopInfo->getGoodsCount());
        $this->assertNull($shopInfo->getImgUrl());
        $this->assertNull($shopInfo->getWxaPath());
        $this->assertNull($shopInfo->getDeliverySign());
    }
}