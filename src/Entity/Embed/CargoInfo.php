<?php

declare(strict_types=1);

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
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '一级分类'])]
    #[TrackColumn]
    private ?string $cargoFirstClass = null;

    /**
     * 二级分类
     */
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '二级分类'])]
    #[TrackColumn]
    private ?string $cargoSecondClass = null;

    /**
     * 货物高度，单位：cm
     */
    #[ORM\Column(type: Types::FLOAT, nullable: true, options: ['comment' => '货物高度，单位：cm'])]
    #[TrackColumn]
    private ?float $goodsHeight = null;

    /**
     * 货物长度，单位：cm
     */
    #[ORM\Column(type: Types::FLOAT, nullable: true, options: ['comment' => '货物长度，单位：cm'])]
    #[TrackColumn]
    private ?float $goodsLength = null;

    /**
     * 货物宽度，单位：cm
     */
    #[ORM\Column(type: Types::FLOAT, nullable: true, options: ['comment' => '货物宽度，单位：cm'])]
    #[TrackColumn]
    private ?float $goodsWidth = null;

    /**
     * 货物重量，单位：kg
     */
    #[ORM\Column(type: Types::FLOAT, nullable: true, options: ['comment' => '货物重量，单位：kg'])]
    #[TrackColumn]
    private ?float $goodsWeight = null;

    /**
     * 货物价格，单位：元
     */
    #[ORM\Column(type: Types::FLOAT, nullable: true, options: ['comment' => '货物价格，单位：元'])]
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
    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '商品数量'])]
    #[TrackColumn]
    private ?int $goodsCount = null;

    public function getCargoFirstClass(): ?string
    {
        return $this->cargoFirstClass;
    }

    public function setCargoFirstClass(?string $cargoFirstClass): void
    {
        $this->cargoFirstClass = $cargoFirstClass;
    }

    public function getCargoSecondClass(): ?string
    {
        return $this->cargoSecondClass;
    }

    public function setCargoSecondClass(?string $cargoSecondClass): void
    {
        $this->cargoSecondClass = $cargoSecondClass;
    }

    public function getGoodsHeight(): ?float
    {
        return $this->goodsHeight;
    }

    public function setGoodsHeight(?float $goodsHeight): void
    {
        $this->goodsHeight = $goodsHeight;
    }

    public function getGoodsLength(): ?float
    {
        return $this->goodsLength;
    }

    public function setGoodsLength(?float $goodsLength): void
    {
        $this->goodsLength = $goodsLength;
    }

    public function getGoodsWidth(): ?float
    {
        return $this->goodsWidth;
    }

    public function setGoodsWidth(?float $goodsWidth): void
    {
        $this->goodsWidth = $goodsWidth;
    }

    public function getGoodsWeight(): ?float
    {
        return $this->goodsWeight;
    }

    public function setGoodsWeight(?float $goodsWeight): void
    {
        $this->goodsWeight = $goodsWeight;
    }

    public function getGoodsValue(): ?float
    {
        return $this->goodsValue;
    }

    public function setGoodsValue(?float $goodsValue): void
    {
        $this->goodsValue = $goodsValue;
    }

    public function getGoodsDetail(): ?string
    {
        return $this->goodsDetail;
    }

    public function setGoodsDetail(?string $goodsDetail): void
    {
        $this->goodsDetail = $goodsDetail;
    }

    public function getGoodsCount(): ?int
    {
        return $this->goodsCount;
    }

    public function setGoodsCount(?int $goodsCount): void
    {
        $this->goodsCount = $goodsCount;
    }

    /**
     * 转换为API请求参数数组
     *
     * @return array<string, mixed>
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
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $info = new self();

        $info->setStringFields($data);
        $info->setNumericFields($data);

        return $info;
    }

    /**
     * 设置字符串字段
     *
     * @param array<string, mixed> $data
     */
    private function setStringFields(array $data): void
    {
        if (isset($data['cargo_first_class'])) {
            $this->setCargoFirstClass($this->convertToStringOrNull($data['cargo_first_class']));
        }

        if (isset($data['cargo_second_class'])) {
            $this->setCargoSecondClass($this->convertToStringOrNull($data['cargo_second_class']));
        }

        if (isset($data['goods_detail'])) {
            $this->setGoodsDetail($this->convertToStringOrNull($data['goods_detail']));
        }
    }

    /**
     * 设置数值字段
     *
     * @param array<string, mixed> $data
     */
    private function setNumericFields(array $data): void
    {
        if (isset($data['goods_height'])) {
            $this->setGoodsHeight($this->convertToFloatOrNull($data['goods_height']));
        }

        if (isset($data['goods_length'])) {
            $this->setGoodsLength($this->convertToFloatOrNull($data['goods_length']));
        }

        if (isset($data['goods_width'])) {
            $this->setGoodsWidth($this->convertToFloatOrNull($data['goods_width']));
        }

        if (isset($data['goods_weight'])) {
            $this->setGoodsWeight($this->convertToFloatOrNull($data['goods_weight']));
        }

        if (isset($data['goods_value'])) {
            $this->setGoodsValue($this->convertToFloatOrNull($data['goods_value']));
        }

        if (isset($data['goods_count'])) {
            $this->setGoodsCount($this->convertToIntOrNull($data['goods_count']));
        }
    }

    /**
     * 安全地将 mixed 值转换为 string 或 null
     */
    private function convertToStringOrNull(mixed $value): ?string
    {
        if (null === $value) {
            return null;
        }

        if (is_string($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (string) $value;
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_object($value) || is_array($value)) {
            $encoded = json_encode($value);

            return false === $encoded ? '' : $encoded;
        }

        if (is_resource($value)) {
            return (string) $value;
        }

        return '';
    }

    /**
     * 安全地将 mixed 值转换为 int 或 null
     */
    private function convertToIntOrNull(mixed $value): ?int
    {
        if (null === $value) {
            return null;
        }

        if (is_int($value)) {
            return $value;
        }

        if (is_float($value)) {
            return (int) $value;
        }

        if (is_string($value) && is_numeric($value)) {
            return (int) $value;
        }

        if (is_bool($value)) {
            return $value ? 1 : 0;
        }

        $filtered = filter_var($value, FILTER_VALIDATE_INT);

        return false !== $filtered ? $filtered : 0;
    }

    /**
     * 安全地将 mixed 值转换为 float 或 null
     */
    private function convertToFloatOrNull(mixed $value): ?float
    {
        if (null === $value) {
            return null;
        }

        if (is_float($value)) {
            return $value;
        }

        if (is_int($value)) {
            return (float) $value;
        }

        if (is_string($value) && is_numeric($value)) {
            return (float) $value;
        }

        if (is_bool($value)) {
            return $value ? 1.0 : 0.0;
        }

        $filtered = filter_var($value, FILTER_VALIDATE_FLOAT);

        return false !== $filtered ? $filtered : 0.0;
    }
}
