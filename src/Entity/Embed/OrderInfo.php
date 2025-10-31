<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\Entity\Embed;

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
    #[ORM\Column(type: 'integer', nullable: true)]
    #[TrackColumn]
    private ?int $orderTime = null;

    /**
     * 订单类型
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    #[TrackColumn]
    private ?int $orderType = null;

    /**
     * 门店订单流水号
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[TrackColumn]
    private ?string $poiSeq = null;

    /**
     * 备注
     */
    #[ORM\Column(type: 'text', nullable: true)]
    #[TrackColumn]
    private ?string $note = null;

    /**
     * 是否选择直拿直送
     */
    #[ORM\Column(type: 'boolean', nullable: true)]
    #[TrackColumn]
    private ?bool $isDirectDelivery = null;

    /**
     * 是否需要取货码
     */
    #[ORM\Column(type: 'boolean', nullable: true)]
    #[TrackColumn]
    private ?bool $isPickupCodeNeeded = null;

    /**
     * 是否需要完成码
     */
    #[ORM\Column(type: 'boolean', nullable: true)]
    #[TrackColumn]
    private ?bool $isFinishCodeNeeded = null;

    /**
     * 期望送达时间（Unix时间戳）
     */
    #[ORM\Column(type: 'integer', nullable: true)]
    #[TrackColumn]
    private ?int $expectedDeliveryTime = null;

    /**
     * 配送服务代码
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[TrackColumn]
    private ?string $deliveryServiceCode = null;

    /**
     * 是否保价
     */
    #[ORM\Column(type: 'boolean', nullable: true)]
    #[TrackColumn]
    private ?bool $isInsured = null;

    /**
     * 小费，单位：元
     */
    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    #[TrackColumn]
    private ?float $tips = null;

    public function getOrderTime(): ?int
    {
        return $this->orderTime;
    }

    public function setOrderTime(?int $orderTime): void
    {
        $this->orderTime = $orderTime;
    }

    public function getOrderType(): ?int
    {
        return $this->orderType;
    }

    public function setOrderType(?int $orderType): void
    {
        $this->orderType = $orderType;
    }

    public function getPoiSeq(): ?string
    {
        return $this->poiSeq;
    }

    public function setPoiSeq(?string $poiSeq): void
    {
        $this->poiSeq = $poiSeq;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): void
    {
        $this->note = $note;
    }

    public function getIsDirectDelivery(): ?bool
    {
        return $this->isDirectDelivery;
    }

    public function setIsDirectDelivery(?bool $isDirectDelivery): void
    {
        $this->isDirectDelivery = $isDirectDelivery;
    }

    public function getIsPickupCodeNeeded(): ?bool
    {
        return $this->isPickupCodeNeeded;
    }

    public function setIsPickupCodeNeeded(?bool $isPickupCodeNeeded): void
    {
        $this->isPickupCodeNeeded = $isPickupCodeNeeded;
    }

    public function getIsFinishCodeNeeded(): ?bool
    {
        return $this->isFinishCodeNeeded;
    }

    public function setIsFinishCodeNeeded(?bool $isFinishCodeNeeded): void
    {
        $this->isFinishCodeNeeded = $isFinishCodeNeeded;
    }

    public function getExpectedDeliveryTime(): ?int
    {
        return $this->expectedDeliveryTime;
    }

    public function setExpectedDeliveryTime(?int $expectedDeliveryTime): void
    {
        $this->expectedDeliveryTime = $expectedDeliveryTime;
    }

    public function getDeliveryServiceCode(): ?string
    {
        return $this->deliveryServiceCode;
    }

    public function setDeliveryServiceCode(?string $deliveryServiceCode): void
    {
        $this->deliveryServiceCode = $deliveryServiceCode;
    }

    public function getIsInsured(): ?bool
    {
        return $this->isInsured;
    }

    public function setIsInsured(?bool $isInsured): void
    {
        $this->isInsured = $isInsured;
    }

    public function getTips(): ?float
    {
        return $this->tips;
    }

    public function setTips(?float $tips): void
    {
        $this->tips = $tips;
    }

    /**
     * 转换为API请求参数数组
     *
     * @return array<string, mixed>
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

        $data = $this->addBooleanFieldsToRequest($data);

        return array_filter($data, fn ($value) => null !== $value);
    }

    /**
     * 添加布尔字段到请求数组
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function addBooleanFieldsToRequest(array $data): array
    {
        $booleanFields = [
            'is_direct_delivery' => $this->getIsDirectDelivery(),
            'is_pickup_code_needed' => $this->getIsPickupCodeNeeded(),
            'is_finish_code_needed' => $this->getIsFinishCodeNeeded(),
            'is_insured' => $this->getIsInsured(),
        ];

        foreach ($booleanFields as $key => $value) {
            if (null !== $value) {
                $data[$key] = $value ? 1 : 0;
            }
        }

        return $data;
    }

    /**
     * 从数组创建实例
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $info = new self();

        $info->setBasicFields($data);
        $info->setBooleanFields($data);
        $info->setNumericFields($data);

        return $info;
    }

    /**
     * 设置基本字段
     *
     * @param array<string, mixed> $data
     */
    private function setBasicFields(array $data): void
    {
        if (isset($data['poi_seq'])) {
            $this->setPoiSeq($this->convertToStringOrNull($data['poi_seq']));
        }

        if (isset($data['note'])) {
            $this->setNote($this->convertToStringOrNull($data['note']));
        }

        if (isset($data['delivery_service_code'])) {
            $this->setDeliveryServiceCode($this->convertToStringOrNull($data['delivery_service_code']));
        }
    }

    /**
     * 设置布尔字段
     *
     * @param array<string, mixed> $data
     */
    private function setBooleanFields(array $data): void
    {
        if (isset($data['is_direct_delivery'])) {
            $this->setIsDirectDelivery((bool) $data['is_direct_delivery']);
        }

        if (isset($data['is_pickup_code_needed'])) {
            $this->setIsPickupCodeNeeded((bool) $data['is_pickup_code_needed']);
        }

        if (isset($data['is_finish_code_needed'])) {
            $this->setIsFinishCodeNeeded((bool) $data['is_finish_code_needed']);
        }

        if (isset($data['is_insured'])) {
            $this->setIsInsured((bool) $data['is_insured']);
        }
    }

    /**
     * 设置数值字段
     *
     * @param array<string, mixed> $data
     */
    private function setNumericFields(array $data): void
    {
        if (isset($data['order_time'])) {
            $this->setOrderTime($this->convertToIntOrNull($data['order_time']));
        }

        if (isset($data['order_type'])) {
            $this->setOrderType($this->convertToIntOrNull($data['order_type']));
        }

        if (isset($data['expected_delivery_time'])) {
            $this->setExpectedDeliveryTime($this->convertToIntOrNull($data['expected_delivery_time']));
        }

        if (isset($data['tips'])) {
            $this->setTips($this->convertToFloatOrNull($data['tips']));
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
