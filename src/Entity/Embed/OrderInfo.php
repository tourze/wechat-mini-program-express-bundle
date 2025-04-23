<?php

namespace WechatMiniProgramExpressBundle\Entity\Embed;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;

/**
 * 订单信息嵌入式实体
 */
#[ORM\Embeddable]
class OrderInfo
{
    /**
     * 下单时间（Unix时间戳）
     */
    #[TrackColumn]
    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '下单时间'])]
    private ?int $orderTime = null;

    /**
     * 订单类型
     */
    #[TrackColumn]
    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '订单类型: 0=即时单, 1=预约单'])]
    private ?int $orderType = null;

    /**
     * 门店订单流水号
     */
    #[TrackColumn]
    #[ORM\Column(type: Types::STRING, length: 64, nullable: true, options: ['comment' => '门店订单流水号'])]
    private ?string $poiSeq = null;

    /**
     * 备注
     */
    #[TrackColumn]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '订单备注'])]
    private ?string $note = null;

    /**
     * 是否选择直拿直送
     */
    #[TrackColumn]
    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '是否选择直拿直送'])]
    private ?bool $isDirectDelivery = null;

    /**
     * 是否需要取货码
     */
    #[TrackColumn]
    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '是否需要取货码'])]
    private ?bool $isPickupCodeNeeded = null;

    /**
     * 是否需要完成码
     */
    #[TrackColumn]
    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '是否需要完成码'])]
    private ?bool $isFinishCodeNeeded = null;

    /**
     * 期望送达时间（Unix时间戳）
     */
    #[TrackColumn]
    #[ORM\Column(type: Types::INTEGER, nullable: true, options: ['comment' => '期望送达时间'])]
    private ?int $expectedDeliveryTime = null;

    /**
     * 配送服务代码
     */
    #[TrackColumn]
    #[ORM\Column(type: Types::STRING, length: 64, nullable: true, options: ['comment' => '配送服务代码'])]
    private ?string $deliveryServiceCode = null;

    /**
     * 是否保价
     */
    #[TrackColumn]
    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '是否保价'])]
    private ?bool $isInsured = null;

    /**
     * 小费，单位：元
     */
    #[TrackColumn]
    #[ORM\Column(type: Types::FLOAT, nullable: true, options: ['comment' => '小费，单位：元'])]
    private ?float $tips = null;

    public function getOrderTime(): ?int
    {
        return $this->orderTime;
    }

    public function setOrderTime(?int $orderTime): self
    {
        $this->orderTime = $orderTime;

        return $this;
    }

    public function getOrderType(): ?int
    {
        return $this->orderType;
    }

    public function setOrderType(?int $orderType): self
    {
        $this->orderType = $orderType;

        return $this;
    }

    public function getPoiSeq(): ?string
    {
        return $this->poiSeq;
    }

    public function setPoiSeq(?string $poiSeq): self
    {
        $this->poiSeq = $poiSeq;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getIsDirectDelivery(): ?bool
    {
        return $this->isDirectDelivery;
    }

    public function setIsDirectDelivery(?bool $isDirectDelivery): self
    {
        $this->isDirectDelivery = $isDirectDelivery;

        return $this;
    }

    public function getIsPickupCodeNeeded(): ?bool
    {
        return $this->isPickupCodeNeeded;
    }

    public function setIsPickupCodeNeeded(?bool $isPickupCodeNeeded): self
    {
        $this->isPickupCodeNeeded = $isPickupCodeNeeded;

        return $this;
    }

    public function getIsFinishCodeNeeded(): ?bool
    {
        return $this->isFinishCodeNeeded;
    }

    public function setIsFinishCodeNeeded(?bool $isFinishCodeNeeded): self
    {
        $this->isFinishCodeNeeded = $isFinishCodeNeeded;

        return $this;
    }

    public function getExpectedDeliveryTime(): ?int
    {
        return $this->expectedDeliveryTime;
    }

    public function setExpectedDeliveryTime(?int $expectedDeliveryTime): self
    {
        $this->expectedDeliveryTime = $expectedDeliveryTime;

        return $this;
    }

    public function getDeliveryServiceCode(): ?string
    {
        return $this->deliveryServiceCode;
    }

    public function setDeliveryServiceCode(?string $deliveryServiceCode): self
    {
        $this->deliveryServiceCode = $deliveryServiceCode;

        return $this;
    }

    public function getIsInsured(): ?bool
    {
        return $this->isInsured;
    }

    public function setIsInsured(?bool $isInsured): self
    {
        $this->isInsured = $isInsured;

        return $this;
    }

    public function getTips(): ?float
    {
        return $this->tips;
    }

    public function setTips(?float $tips): self
    {
        $this->tips = $tips;

        return $this;
    }

    /**
     * 转换为API请求参数数组
     */
    public function toRequestArray(): array
    {
        $data = [
            'order_time' => $this->getOrderTime(),
            'order_type' => $this->getOrderType(),
            'poi_seq' => $this->getPoiSeq(),
            'note' => $this->getNote(),
            'delivery_service_code' => $this->getDeliveryServiceCode(),
            'expected_delivery_time' => $this->getExpectedDeliveryTime(),
            'tips' => $this->getTips(),
        ];

        // 布尔值转为0/1
        if (null !== $this->getIsDirectDelivery()) {
            $data['is_direct_delivery'] = $this->getIsDirectDelivery() ? 1 : 0;
        }

        if (null !== $this->getIsPickupCodeNeeded()) {
            $data['is_pickup_code_needed'] = $this->getIsPickupCodeNeeded() ? 1 : 0;
        }

        if (null !== $this->getIsFinishCodeNeeded()) {
            $data['is_finish_code_needed'] = $this->getIsFinishCodeNeeded() ? 1 : 0;
        }

        if (null !== $this->getIsInsured()) {
            $data['is_insured'] = $this->getIsInsured() ? 1 : 0;
        }

        return array_filter($data, fn ($value) => null !== $value);
    }

    /**
     * 从数组创建实例
     */
    public static function fromArray(array $data): self
    {
        $info = new self();

        if (isset($data['order_time'])) {
            $info->setOrderTime((int) $data['order_time']);
        }

        if (isset($data['order_type'])) {
            $info->setOrderType((int) $data['order_type']);
        }

        if (isset($data['poi_seq'])) {
            $info->setPoiSeq($data['poi_seq']);
        }

        if (isset($data['note'])) {
            $info->setNote($data['note']);
        }

        if (isset($data['is_direct_delivery'])) {
            $info->setIsDirectDelivery((bool) $data['is_direct_delivery']);
        }

        if (isset($data['is_pickup_code_needed'])) {
            $info->setIsPickupCodeNeeded((bool) $data['is_pickup_code_needed']);
        }

        if (isset($data['is_finish_code_needed'])) {
            $info->setIsFinishCodeNeeded((bool) $data['is_finish_code_needed']);
        }

        if (isset($data['expected_delivery_time'])) {
            $info->setExpectedDeliveryTime((int) $data['expected_delivery_time']);
        }

        if (isset($data['delivery_service_code'])) {
            $info->setDeliveryServiceCode($data['delivery_service_code']);
        }

        if (isset($data['is_insured'])) {
            $info->setIsInsured((bool) $data['is_insured']);
        }

        if (isset($data['tips'])) {
            $info->setTips((float) $data['tips']);
        }

        return $info;
    }
}
