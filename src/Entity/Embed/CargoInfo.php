<?php

namespace WechatMiniProgramExpressBundle\Entity\Embed;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;

/**
 * 货物信息嵌入式实体
 */
#[ORM\Embeddable]
class CargoInfo
{
    /**
     * 一级分类
     */
    #[TrackColumn]
    private ?string $cargoFirstClass = null;

    /**
     * 二级分类
     */
    #[TrackColumn]
    private ?string $cargoSecondClass = null;

    /**
     * 货物高度，单位：cm
     */
    #[TrackColumn]
    private ?float $goodsHeight = null;

    /**
     * 货物长度，单位：cm
     */
    #[TrackColumn]
    private ?float $goodsLength = null;

    /**
     * 货物宽度，单位：cm
     */
    #[TrackColumn]
    private ?float $goodsWidth = null;

    /**
     * 货物重量，单位：kg
     */
    #[TrackColumn]
    private ?float $goodsWeight = null;

    /**
     * 货物价格，单位：元
     */
    #[TrackColumn]
    private ?float $goodsValue = null;

    /**
     * 商品详情
     */
    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '商品详情'])]
    private ?string $goodsDetail = null;

    /**
     * 商品数量
     */
    #[TrackColumn]
    private ?int $goodsCount = null;

    public function getCargoFirstClass(): ?string
    {
        return $this->cargoFirstClass;
    }

    public function setCargoFirstClass(?string $cargoFirstClass): self
    {
        $this->cargoFirstClass = $cargoFirstClass;

        return $this;
    }

    public function getCargoSecondClass(): ?string
    {
        return $this->cargoSecondClass;
    }

    public function setCargoSecondClass(?string $cargoSecondClass): self
    {
        $this->cargoSecondClass = $cargoSecondClass;

        return $this;
    }

    public function getGoodsHeight(): ?float
    {
        return $this->goodsHeight;
    }

    public function setGoodsHeight(?float $goodsHeight): self
    {
        $this->goodsHeight = $goodsHeight;

        return $this;
    }

    public function getGoodsLength(): ?float
    {
        return $this->goodsLength;
    }

    public function setGoodsLength(?float $goodsLength): self
    {
        $this->goodsLength = $goodsLength;

        return $this;
    }

    public function getGoodsWidth(): ?float
    {
        return $this->goodsWidth;
    }

    public function setGoodsWidth(?float $goodsWidth): self
    {
        $this->goodsWidth = $goodsWidth;

        return $this;
    }

    public function getGoodsWeight(): ?float
    {
        return $this->goodsWeight;
    }

    public function setGoodsWeight(?float $goodsWeight): self
    {
        $this->goodsWeight = $goodsWeight;

        return $this;
    }

    public function getGoodsValue(): ?float
    {
        return $this->goodsValue;
    }

    public function setGoodsValue(?float $goodsValue): self
    {
        $this->goodsValue = $goodsValue;

        return $this;
    }

    public function getGoodsDetail(): ?string
    {
        return $this->goodsDetail;
    }

    public function setGoodsDetail(?string $goodsDetail): self
    {
        $this->goodsDetail = $goodsDetail;

        return $this;
    }

    public function getGoodsCount(): ?int
    {
        return $this->goodsCount;
    }

    public function setGoodsCount(?int $goodsCount): self
    {
        $this->goodsCount = $goodsCount;

        return $this;
    }

    /**
     * 转换为API请求参数数组
     */
    public function toRequestArray(): array
    {
        $data = [
            'cargo_first_class' => $this->getCargoFirstClass(),
            'cargo_second_class' => $this->getCargoSecondClass(),
            'goods_height' => $this->getGoodsHeight(),
            'goods_length' => $this->getGoodsLength(),
            'goods_width' => $this->getGoodsWidth(),
            'goods_weight' => $this->getGoodsWeight(),
            'goods_value' => $this->getGoodsValue(),
        ];

        if (null !== $this->getGoodsDetail()) {
            $data['goods_detail'] = $this->getGoodsDetail();
        }
        
        if (null !== $this->getGoodsCount()) {
            $data['goods_count'] = $this->getGoodsCount();
        }

        return array_filter($data, fn ($value) => null !== $value);
    }

    /**
     * 从数组创建实例
     */
    public static function fromArray(array $data): self
    {
        $info = new self();

        if ((bool) isset($data['cargo_first_class'])) {
            $info->setCargoFirstClass($data['cargo_first_class']);
        }

        if ((bool) isset($data['cargo_second_class'])) {
            $info->setCargoSecondClass($data['cargo_second_class']);
        }

        if ((bool) isset($data['goods_height'])) {
            $info->setGoodsHeight((float) $data['goods_height']);
        }

        if ((bool) isset($data['goods_length'])) {
            $info->setGoodsLength((float) $data['goods_length']);
        }

        if ((bool) isset($data['goods_width'])) {
            $info->setGoodsWidth((float) $data['goods_width']);
        }

        if ((bool) isset($data['goods_weight'])) {
            $info->setGoodsWeight((float) $data['goods_weight']);
        }

        if ((bool) isset($data['goods_value'])) {
            $info->setGoodsValue((float) $data['goods_value']);
        }

        if ((bool) isset($data['goods_detail'])) {
            $info->setGoodsDetail($data['goods_detail']);
        }
        
        if ((bool) isset($data['goods_count'])) {
            $info->setGoodsCount((int) $data['goods_count']);
        }

        return $info;
    }
}
