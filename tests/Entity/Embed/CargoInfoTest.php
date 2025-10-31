<?php

namespace WechatMiniProgramExpressBundle\Tests\Entity\Embed;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Entity\Embed\CargoInfo;

/**
 * @internal
 */
#[CoversClass(CargoInfo::class)]
final class CargoInfoTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $entity = new CargoInfo();

        $entity->setCargoFirstClass('first_class');
        $this->assertSame('first_class', $entity->getCargoFirstClass());

        $entity->setCargoSecondClass('second_class');
        $this->assertSame('second_class', $entity->getCargoSecondClass());

        $entity->setGoodsHeight(10.5);
        $this->assertSame(10.5, $entity->getGoodsHeight());

        $entity->setGoodsLength(20.5);
        $this->assertSame(20.5, $entity->getGoodsLength());

        $entity->setGoodsWidth(15.0);
        $this->assertSame(15.0, $entity->getGoodsWidth());

        $entity->setGoodsWeight(2.5);
        $this->assertSame(2.5, $entity->getGoodsWeight());

        $entity->setGoodsValue(100.0);
        $this->assertSame(100.0, $entity->getGoodsValue());

        $entity->setGoodsDetail('商品详情');
        $this->assertSame('商品详情', $entity->getGoodsDetail());

        $entity->setGoodsCount(5);
        $this->assertSame(5, $entity->getGoodsCount());
    }

    public function testToRequestArray(): void
    {
        $entity = new CargoInfo();
        $entity->setCargoFirstClass('first_class');
        $entity->setCargoSecondClass('second_class');
        $entity->setGoodsHeight(10.5);
        $entity->setGoodsLength(20.5);
        $entity->setGoodsWidth(15.0);
        $entity->setGoodsWeight(2.5);
        $entity->setGoodsValue(100.0);
        $entity->setGoodsDetail('商品详情');
        $entity->setGoodsCount(5);

        $array = $entity->toRequestArray();

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
        $entity = new CargoInfo();
        $entity->setCargoFirstClass('first_class');
        // 其他值保持null

        $array = $entity->toRequestArray();

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
