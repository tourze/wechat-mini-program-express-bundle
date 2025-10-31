<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\Entity\Embed;

use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;

/**
 * 收件人信息嵌入式实体
 */
#[ORM\Embeddable]
class ReceiverInfo
{
    /**
     * 姓名
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true, options: ['comment' => '姓名'])]
    #[TrackColumn]
    private ?string $name = null;

    /**
     * 电话
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true, options: ['comment' => '电话'])]
    #[TrackColumn]
    private ?string $phone = null;

    /**
     * 城市
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true, options: ['comment' => '城市'])]
    #[TrackColumn]
    private ?string $city = null;

    /**
     * 地址
     */
    #[ORM\Column(type: 'string', length: 500, nullable: true, options: ['comment' => '地址'])]
    #[TrackColumn]
    private ?string $address = null;

    /**
     * 地址详情
     */
    #[ORM\Column(type: 'string', length: 500, nullable: true, options: ['comment' => '地址详情'])]
    #[TrackColumn]
    private ?string $addressDetail = null;

    /**
     * 经度
     */
    #[ORM\Column(type: 'float', nullable: true, options: ['comment' => '经度'])]
    #[TrackColumn]
    private ?float $lng = null;

    /**
     * 纬度
     */
    #[ORM\Column(type: 'float', nullable: true, options: ['comment' => '纬度'])]
    #[TrackColumn]
    private ?float $lat = null;

    /**
     * 坐标类型
     */
    #[ORM\Column(type: 'integer', nullable: true, options: ['comment' => '坐标类型'])]
    #[TrackColumn]
    private ?int $coordinateType = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function getAddressDetail(): ?string
    {
        return $this->addressDetail;
    }

    public function setAddressDetail(?string $addressDetail): void
    {
        $this->addressDetail = $addressDetail;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(?float $lng): void
    {
        $this->lng = $lng;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(?float $lat): void
    {
        $this->lat = $lat;
    }

    public function getCoordinateType(): ?int
    {
        return $this->coordinateType;
    }

    public function setCoordinateType(?int $coordinateType): void
    {
        $this->coordinateType = $coordinateType;
    }

    /**
     * 转换为API请求参数数组
     *
     * @return array<string, mixed>
     */
    public function toRequestArray(): array
    {
        $data = [
            'name' => $this->getName(),
            'mobile' => $this->getPhone(),
            'city' => $this->getCity(),
            'address' => $this->getAddress(),
            'address_detail' => $this->getAddressDetail(),
        ];

        // 添加可选的坐标信息
        if (null !== $this->getLng() && null !== $this->getLat()) {
            $data['lng'] = $this->getLng();
            $data['lat'] = $this->getLat();
            $data['coordinate_type'] = $this->getCoordinateType() ?? 0;
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

        if (isset($data['name'])) {
            $info->setName(self::convertToStringOrNull($data['name']));
        }

        if (isset($data['phone'])) {
            $info->setPhone(self::convertToStringOrNull($data['phone']));
        }

        if (isset($data['city'])) {
            $info->setCity(self::convertToStringOrNull($data['city']));
        }

        if (isset($data['address'])) {
            $info->setAddress(self::convertToStringOrNull($data['address']));
        }

        if (isset($data['address_detail'])) {
            $info->setAddressDetail(self::convertToStringOrNull($data['address_detail']));
        }

        if (isset($data['lng'])) {
            $info->setLng(self::convertToFloatOrNull($data['lng']));
        }

        if (isset($data['lat'])) {
            $info->setLat(self::convertToFloatOrNull($data['lat']));
        }

        if (isset($data['coordinate_type'])) {
            $info->setCoordinateType(self::convertToIntOrNull($data['coordinate_type']));
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

    /**
     * 安全地将 mixed 值转换为 float 或 null
     */
    private static function convertToFloatOrNull(mixed $value): ?float
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
