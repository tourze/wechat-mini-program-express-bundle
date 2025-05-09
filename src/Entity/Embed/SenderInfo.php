<?php

namespace WechatMiniProgramExpressBundle\Entity\Embed;

use Doctrine\DBAL\Types\Types;
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
    #[ORM\Column(type: Types::STRING, length: 64, nullable: true, options: ['comment' => '发件人姓名'])]
    private ?string $name = null;

    /**
     * 电话
     */
    #[TrackColumn]
    #[ORM\Column(type: Types::STRING, length: 32, nullable: true, options: ['comment' => '发件人电话'])]
    private ?string $phone = null;

    /**
     * 城市
     */
    #[TrackColumn]
    #[ORM\Column(type: Types::STRING, length: 64, nullable: true, options: ['comment' => '发件人城市'])]
    private ?string $city = null;

    /**
     * 地址
     */
    #[TrackColumn]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '发件人地址'])]
    private ?string $address = null;

    /**
     * 地址详情
     */
    #[TrackColumn]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '发件人地址详情'])]
    private ?string $addressDetail = null;

    /**
     * 经度
     */
    #[TrackColumn]
    #[ORM\Column(type: Types::FLOAT, nullable: true, options: ['comment' => '发件人坐标经度'])]
    private ?float $lng = null;

    /**
     * 纬度
     */
    #[TrackColumn]
    #[ORM\Column(type: Types::FLOAT, nullable: true, options: ['comment' => '发件人坐标纬度'])]
    private ?float $lat = null;

    /**
     * 坐标类型
     */
    #[TrackColumn]
    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '坐标类型: 0=腾讯, 1=百度, 2=高德'])]
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
            'phone' => $this->getPhone(),
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
     * 兼容测试，设置手机号，实际调用setPhone方法
     */
    public function setMobile(?string $mobile): self
    {
        return $this->setPhone($mobile);
    }

    /**
     * 从数组创建实例
     */
    public static function fromArray(array $data): self
    {
        $info = new self();

        if (isset($data['name'])) {
            $info->setName($data['name']);
        }

        if (isset($data['phone'])) {
            $info->setPhone($data['phone']);
        }

        if (isset($data['city'])) {
            $info->setCity($data['city']);
        }

        if (isset($data['address'])) {
            $info->setAddress($data['address']);
        }

        if (isset($data['address_detail'])) {
            $info->setAddressDetail($data['address_detail']);
        }

        if (isset($data['lng'])) {
            $info->setLng((float) $data['lng']);
        }

        if (isset($data['lat'])) {
            $info->setLat((float) $data['lat']);
        }

        if (isset($data['coordinate_type'])) {
            $info->setCoordinateType((int) $data['coordinate_type']);
        }

        return $info;
    }
}
