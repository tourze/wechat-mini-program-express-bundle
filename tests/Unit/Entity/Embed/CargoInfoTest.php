<?php

namespace WechatMiniProgramExpressBundle\Tests\Unit\Entity\Embed;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Entity\Embed\CargoInfo;

class CargoInfoTest extends TestCase
{
    private CargoInfo $cargoInfo;

    protected function setUp(): void
    {
        $this->cargoInfo = new CargoInfo();
    }

    public function testGettersAndSetters(): void
    {
        $cargoFirstClass = 'first_class';
        $cargoSecondClass = 'second_class';
        $goodsHeight = 10.5;
        $goodsLength = 20.5;
        $goodsWidth = 15.0;
        $goodsWeight = 2.5;
        $goodsValue = 100.0;
        $goodsDetail = '商品详情';
        $goodsCount = 5;

        $this->cargoInfo->setCargoFirstClass($cargoFirstClass);
        $this->cargoInfo->setCargoSecondClass($cargoSecondClass);
        $this->cargoInfo->setGoodsHeight($goodsHeight);
        $this->cargoInfo->setGoodsLength($goodsLength);
        $this->cargoInfo->setGoodsWidth($goodsWidth);
        $this->cargoInfo->setGoodsWeight($goodsWeight);
        $this->cargoInfo->setGoodsValue($goodsValue);
        $this->cargoInfo->setGoodsDetail($goodsDetail);
        $this->cargoInfo->setGoodsCount($goodsCount);

        $this->assertSame($cargoFirstClass, $this->cargoInfo->getCargoFirstClass());
        $this->assertSame($cargoSecondClass, $this->cargoInfo->getCargoSecondClass());
        $this->assertSame($goodsHeight, $this->cargoInfo->getGoodsHeight());
        $this->assertSame($goodsLength, $this->cargoInfo->getGoodsLength());
        $this->assertSame($goodsWidth, $this->cargoInfo->getGoodsWidth());
        $this->assertSame($goodsWeight, $this->cargoInfo->getGoodsWeight());
        $this->assertSame($goodsValue, $this->cargoInfo->getGoodsValue());
        $this->assertSame($goodsDetail, $this->cargoInfo->getGoodsDetail());
        $this->assertSame($goodsCount, $this->cargoInfo->getGoodsCount());
    }

    public function testToRequestArray(): void
    {
        $this->cargoInfo->setCargoFirstClass('first_class');
        $this->cargoInfo->setCargoSecondClass('second_class');
        $this->cargoInfo->setGoodsHeight(10.5);
        $this->cargoInfo->setGoodsLength(20.5);
        $this->cargoInfo->setGoodsWidth(15.0);
        $this->cargoInfo->setGoodsWeight(2.5);
        $this->cargoInfo->setGoodsValue(100.0);
        $this->cargoInfo->setGoodsDetail('商品详情');
        $this->cargoInfo->setGoodsCount(5);

        $array = $this->cargoInfo->toRequestArray();

        $this->assertArrayHasKey('cargo_first_class', $array);
        $this->assertArrayHasKey('cargo_second_class', $array);
        $this->assertArrayHasKey('goods_height', $array);
        $this->assertArrayHasKey('goods_length', $array);
        $this->assertArrayHasKey('goods_width', $array);
        $this->assertArrayHasKey('goods_weight', $array);
        $this->assertArrayHasKey('goods_value', $array);
        $this->assertArrayHasKey('goods_detail', $array);
        $this->assertArrayHasKey('goods_count', $array);

        $this->assertSame('first_class', $array['cargo_first_class']);
        $this->assertSame('second_class', $array['cargo_second_class']);
        $this->assertSame(10.5, $array['goods_height']);
        $this->assertSame(20.5, $array['goods_length']);
        $this->assertSame(15.0, $array['goods_width']);
        $this->assertSame(2.5, $array['goods_weight']);
        $this->assertSame(100.0, $array['goods_value']);
        $this->assertSame('商品详情', $array['goods_detail']);
        $this->assertSame(5, $array['goods_count']);
    }

    public function testToRequestArrayFiltersNullValues(): void
    {
        $this->cargoInfo->setCargoFirstClass('first_class');
        // 其他值保持null

        $array = $this->cargoInfo->toRequestArray();

        $this->assertArrayHasKey('cargo_first_class', $array);
        $this->assertArrayNotHasKey('cargo_second_class', $array);
        $this->assertArrayNotHasKey('goods_height', $array);
    }

    public function testFromArray(): void
    {
        $data = [
            'cargo_first_class' => 'first_class',
            'cargo_second_class' => 'second_class',
            'goods_height' => 10.5,
            'goods_length' => 20.5,
            'goods_width' => 15.0,
            'goods_weight' => 2.5,
            'goods_value' => 100.0,
            'goods_detail' => '商品详情',
            'goods_count' => 5,
        ];

        $cargoInfo = CargoInfo::fromArray($data);

        $this->assertSame('first_class', $cargoInfo->getCargoFirstClass());
        $this->assertSame('second_class', $cargoInfo->getCargoSecondClass());
        $this->assertSame(10.5, $cargoInfo->getGoodsHeight());
        $this->assertSame(20.5, $cargoInfo->getGoodsLength());
        $this->assertSame(15.0, $cargoInfo->getGoodsWidth());
        $this->assertSame(2.5, $cargoInfo->getGoodsWeight());
        $this->assertSame(100.0, $cargoInfo->getGoodsValue());
        $this->assertSame('商品详情', $cargoInfo->getGoodsDetail());
        $this->assertSame(5, $cargoInfo->getGoodsCount());
    }

    public function testFromArrayWithPartialData(): void
    {
        $data = [
            'cargo_first_class' => 'first_class',
            'goods_height' => 10.5,
        ];

        $cargoInfo = CargoInfo::fromArray($data);

        $this->assertSame('first_class', $cargoInfo->getCargoFirstClass());
        $this->assertSame(10.5, $cargoInfo->getGoodsHeight());
        $this->assertNull($cargoInfo->getCargoSecondClass());
        $this->assertNull($cargoInfo->getGoodsLength());
    }

    public function testFromArrayWithEmptyData(): void
    {
        $cargoInfo = CargoInfo::fromArray([]);

        $this->assertNull($cargoInfo->getCargoFirstClass());
        $this->assertNull($cargoInfo->getCargoSecondClass());
        $this->assertNull($cargoInfo->getGoodsHeight());
    }
}