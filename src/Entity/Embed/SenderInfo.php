<?php

namespace WechatMiniProgramExpressBundle\Entity\Embed;

use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;

/**
 * 发件人信息嵌入式实体
 */
#[ORM\Embeddable]
class SenderInfo
{
    /**
     * 姓名
     */
    #[TrackColumn]
    private ?string $name = null;

    /**
     * 电话
     */
    #[TrackColumn]
    private ?string $phone = null;

    /**
     * 城市
     */
    #[TrackColumn]
    private ?string $city = null;

    /**
     * 地址
     */
    #[TrackColumn]
    private ?string $address = null;

    /**
     * 地址详情
     */
    #[TrackColumn]
    private ?string $addressDetail = null;

    /**
     * 经度
     */
    #[TrackColumn]
    private ?float $lng = null;

    /**
     * 纬度
     */
    #[TrackColumn]
    private ?float $lat = null;

    /**
     * 坐标类型
     */
    #[TrackColumn]
    private ?int $coordinateType = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getAddressDetail(): ?string
    {
        return $this->addressDetail;
    }

    public function setAddressDetail(?string $addressDetail): self
    {
        $this->addressDetail = $addressDetail;

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(?float $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(?float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getCoordinateType(): ?int
    {
        return $this->coordinateType;
    }

    public function setCoordinateType(?int $coordinateType): self
    {
        $this->coordinateType = $coordinateType;

        return $this;
    }

    /**
     * 转换为API请求参数数组
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
     */
    public static function fromArray(array $data): self
    {
        $info = new self();

        if ((bool) isset($data['name'])) {
            $info->setName($data['name']);
        }

        if ((bool) isset($data['phone'])) {
            $info->setPhone($data['phone']);
        }

        if ((bool) isset($data['city'])) {
            $info->setCity($data['city']);
        }

        if ((bool) isset($data['address'])) {
            $info->setAddress($data['address']);
        }

        if ((bool) isset($data['address_detail'])) {
            $info->setAddressDetail($data['address_detail']);
        }

        if ((bool) isset($data['lng'])) {
            $info->setLng((float) $data['lng']);
        }

        if ((bool) isset($data['lat'])) {
            $info->setLat((float) $data['lat']);
        }

        if ((bool) isset($data['coordinate_type'])) {
            $info->setCoordinateType((int) $data['coordinate_type']);
        }

        return $info;
    }
}
