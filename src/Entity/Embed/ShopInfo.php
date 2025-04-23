<?php

namespace WechatMiniProgramExpressBundle\Entity\Embed;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;

/**
 * 商品信息嵌入式实体
 */
#[ORM\Embeddable]
class ShopInfo
{
    /**
     * 商品名称
     */
    #[TrackColumn]
    #[ORM\Column(type: Types::STRING, length: 128, nullable: true, options: ['comment' => '商品名称'])]
    private ?string $goodsName = null;

    /**
     * 商品数量
     */
    #[TrackColumn]
    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '商品数量'])]
    private ?int $goodsCount = null;

    /**
     * 商品图片链接
     */
    #[TrackColumn]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '商品图片链接'])]
    private ?string $imgUrl = null;

    /**
     * 商品小程序路径
     */
    #[TrackColumn]
    #[ORM\Column(type: Types::STRING, length: 128, nullable: true, options: ['comment' => '商品小程序路径'])]
    private ?string $wxaPath = null;

    public function getGoodsName(): ?string
    {
        return $this->goodsName;
    }

    public function setGoodsName(?string $goodsName): self
    {
        $this->goodsName = $goodsName;

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

    public function getImgUrl(): ?string
    {
        return $this->imgUrl;
    }

    public function setImgUrl(?string $imgUrl): self
    {
        $this->imgUrl = $imgUrl;

        return $this;
    }

    public function getWxaPath(): ?string
    {
        return $this->wxaPath;
    }

    public function setWxaPath(?string $wxaPath): self
    {
        $this->wxaPath = $wxaPath;

        return $this;
    }

    /**
     * 转换为API请求参数数组
     */
    public function toRequestArray(): array
    {
        $data = [
            'goods_name' => $this->getGoodsName(),
            'goods_count' => $this->getGoodsCount(),
            'img_url' => $this->getImgUrl(),
            'wxa_path' => $this->getWxaPath(),
        ];

        return array_filter($data, fn ($value) => null !== $value);
    }

    /**
     * 从数组创建实例
     */
    public static function fromArray(array $data): self
    {
        $info = new self();

        if (isset($data['goods_name'])) {
            $info->setGoodsName($data['goods_name']);
        }

        if (isset($data['goods_count'])) {
            $info->setGoodsCount((int) $data['goods_count']);
        }

        if (isset($data['img_url'])) {
            $info->setImgUrl($data['img_url']);
        }

        if (isset($data['wxa_path'])) {
            $info->setWxaPath($data['wxa_path']);
        }

        return $info;
    }
}
