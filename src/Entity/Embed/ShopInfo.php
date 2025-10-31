<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\Entity\Embed;

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
    #[ORM\Column(type: 'string', length: 255, nullable: true, options: ['comment' => '商品名称'])]
    #[TrackColumn]
    private ?string $goodsName = null;

    /**
     * 商品数量
     */
    #[ORM\Column(type: 'integer', nullable: true, options: ['comment' => '商品数量'])]
    #[TrackColumn]
    private ?int $goodsCount = null;

    /**
     * 商品图片链接
     */
    #[ORM\Column(type: 'string', length: 500, nullable: true, options: ['comment' => '商品图片链接'])]
    #[TrackColumn]
    private ?string $imgUrl = null;

    /**
     * 商品小程序路径
     */
    #[ORM\Column(type: 'string', length: 500, nullable: true, options: ['comment' => '商品小程序路径'])]
    #[TrackColumn]
    private ?string $wxaPath = null;

    /**
     * 微信门店ID
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true, options: ['comment' => '微信门店ID'])]
    #[TrackColumn]
    private ?string $wcPoi = null;

    /**
     * 门店订单ID
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true, options: ['comment' => '门店订单ID'])]
    #[TrackColumn]
    private ?string $shopOrderId = null;

    /**
     * 配送签名
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true, options: ['comment' => '配送签名'])]
    #[TrackColumn]
    private ?string $deliverySign = null;

    public function getGoodsName(): ?string
    {
        return $this->goodsName;
    }

    public function setGoodsName(?string $goodsName): void
    {
        $this->goodsName = $goodsName;
    }

    public function getGoodsCount(): ?int
    {
        return $this->goodsCount;
    }

    public function setGoodsCount(?int $goodsCount): void
    {
        $this->goodsCount = $goodsCount;
    }

    public function getImgUrl(): ?string
    {
        return $this->imgUrl;
    }

    public function setImgUrl(?string $imgUrl): void
    {
        $this->imgUrl = $imgUrl;
    }

    public function getWxaPath(): ?string
    {
        return $this->wxaPath;
    }

    public function setWxaPath(?string $wxaPath): void
    {
        $this->wxaPath = $wxaPath;
    }

    /**
     * 兼容测试，设置微信小程序AppID，实际调用setWxaPath方法
     */
    public function setWechatAppId(?string $wechatAppId): void
    {
        $this->setWxaPath($wechatAppId);
    }

    public function getWcPoi(): ?string
    {
        return $this->wcPoi;
    }

    public function setWcPoi(?string $wcPoi): void
    {
        $this->wcPoi = $wcPoi;
    }

    public function getShopOrderId(): ?string
    {
        return $this->shopOrderId;
    }

    public function setShopOrderId(?string $shopOrderId): void
    {
        $this->shopOrderId = $shopOrderId;
    }

    public function getDeliverySign(): ?string
    {
        return $this->deliverySign;
    }

    public function setDeliverySign(?string $deliverySign): void
    {
        $this->deliverySign = $deliverySign;
    }

    /**
     * 转换为API请求参数数组
     *
     * @return array<string, mixed>
     */
    public function toRequestArray(): array
    {
        $data = [
            'goods_name' => $this->getGoodsName(),
            'goods_count' => $this->getGoodsCount(),
            'img_url' => $this->getImgUrl(),
            'wxa_path' => $this->getWxaPath(),
        ];

        if (null !== $this->getWcPoi()) {
            $data['wc_poi'] = $this->getWcPoi();
        }

        if (null !== $this->getShopOrderId()) {
            $data['shop_order_id'] = $this->getShopOrderId();
        }

        if (null !== $this->getDeliverySign()) {
            $data['delivery_sign'] = $this->getDeliverySign();
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

        if (isset($data['goods_name'])) {
            $info->setGoodsName(self::convertToStringOrNull($data['goods_name']));
        }

        if (isset($data['goods_count'])) {
            $info->setGoodsCount(self::convertToIntOrNull($data['goods_count']));
        }

        if (isset($data['img_url'])) {
            $info->setImgUrl(self::convertToStringOrNull($data['img_url']));
        }

        if (isset($data['wxa_path'])) {
            $info->setWxaPath(self::convertToStringOrNull($data['wxa_path']));
        }

        if (isset($data['wc_poi'])) {
            $info->setWcPoi(self::convertToStringOrNull($data['wc_poi']));
        }

        if (isset($data['shop_order_id'])) {
            $info->setShopOrderId(self::convertToStringOrNull($data['shop_order_id']));
        }

        if (isset($data['delivery_sign'])) {
            $info->setDeliverySign(self::convertToStringOrNull($data['delivery_sign']));
        }

        return $info;
    }

    /**
     * 安全地将 mixed 值转换为 string 或 null
     */
    private static function convertToStringOrNull(mixed $value): ?string
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
    private static function convertToIntOrNull(mixed $value): ?int
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
}
