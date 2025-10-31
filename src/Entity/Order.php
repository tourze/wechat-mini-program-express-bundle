<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;
use WechatMiniProgramExpressBundle\Entity\Embed\CargoInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\OrderInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\ReceiverInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\SenderInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\ShopInfo;

/**
 * 即时配送订单实体
 */
#[ORM\Entity]
#[ORM\Table(name: 'delivery_order', options: ['comment' => '表描述'])]
class Order implements \Stringable
{
    use TimestampableAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '订单ID'])]
    private ?int $id = null;

    #[TrackColumn]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '微信侧订单ID'])]
    #[Assert\Type(type: 'string')]
    #[Assert\Length(max: 255)]
    private ?string $wechatOrderId = null;

    #[TrackColumn]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '配送单号'])]
    #[Assert\Type(type: 'string')]
    #[Assert\Length(max: 255)]
    private ?string $deliveryId = null;

    #[TrackColumn]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '配送状态'])]
    #[Assert\Type(type: 'string')]
    #[Assert\Length(max: 255)]
    private ?string $status = null;

    #[TrackColumn]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '配送费用（单位：元）'])]
    #[Assert\Type(type: 'string')]
    #[Assert\Length(max: 255)]
    private ?string $fee = null;

    #[TrackColumn]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '配送公司ID'])]
    #[Assert\Type(type: 'string')]
    #[Assert\Length(max: 255)]
    private ?string $deliveryCompanyId = null;

    #[TrackColumn]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '账号绑定ID'])]
    #[Assert\Type(type: 'string')]
    #[Assert\Length(max: 255)]
    private ?string $bindAccountId = null;

    /**
     * 发送方信息
     */
    #[ORM\Embedded(class: SenderInfo::class)]
    #[Assert\Valid]
    private SenderInfo $senderInfo;

    /**
     * 接收方信息
     */
    #[ORM\Embedded(class: ReceiverInfo::class)]
    #[Assert\Valid]
    private ReceiverInfo $receiverInfo;

    /**
     * 货物信息
     */
    #[ORM\Embedded(class: CargoInfo::class)]
    #[Assert\Valid]
    private CargoInfo $cargoInfo;

    /**
     * 订单信息
     */
    #[ORM\Embedded(class: OrderInfo::class)]
    #[Assert\Valid]
    private OrderInfo $orderInfo;

    /**
     * 商品信息
     */
    #[ORM\Embedded(class: ShopInfo::class)]
    #[Assert\Valid]
    private ShopInfo $shopInfo;

    /** @var array<string, mixed>|null */
    #[TrackColumn]
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '原始请求数据'])]
    #[Assert\Type(type: 'array')]
    private ?array $requestData = null;

    /** @var array<string, mixed>|null */
    #[TrackColumn]
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '原始响应数据'])]
    #[Assert\Type(type: 'array')]
    private ?array $responseData = null;

    public function __construct()
    {
        $this->senderInfo = new SenderInfo();
        $this->receiverInfo = new ReceiverInfo();
        $this->cargoInfo = new CargoInfo();
        $this->orderInfo = new OrderInfo();
        $this->shopInfo = new ShopInfo();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWechatOrderId(): ?string
    {
        return $this->wechatOrderId;
    }

    public function setWechatOrderId(?string $wechatOrderId): void
    {
        $this->wechatOrderId = $wechatOrderId;
    }

    public function getDeliveryId(): ?string
    {
        return $this->deliveryId;
    }

    public function setDeliveryId(?string $deliveryId): void
    {
        $this->deliveryId = $deliveryId;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function getFee(): ?string
    {
        return $this->fee;
    }

    public function setFee(?string $fee): void
    {
        $this->fee = $fee;
    }

    public function getDeliveryCompanyId(): ?string
    {
        return $this->deliveryCompanyId;
    }

    public function setDeliveryCompanyId(?string $deliveryCompanyId): void
    {
        $this->deliveryCompanyId = $deliveryCompanyId;
    }

    public function getBindAccountId(): ?string
    {
        return $this->bindAccountId;
    }

    public function setBindAccountId(?string $bindAccountId): void
    {
        $this->bindAccountId = $bindAccountId;
    }

    public function getSenderInfo(): SenderInfo
    {
        return $this->senderInfo;
    }

    public function setSenderInfo(SenderInfo $senderInfo): void
    {
        $this->senderInfo = $senderInfo;
    }

    public function getReceiverInfo(): ReceiverInfo
    {
        return $this->receiverInfo;
    }

    public function setReceiverInfo(ReceiverInfo $receiverInfo): void
    {
        $this->receiverInfo = $receiverInfo;
    }

    public function getCargoInfo(): CargoInfo
    {
        return $this->cargoInfo;
    }

    public function setCargoInfo(CargoInfo $cargoInfo): void
    {
        $this->cargoInfo = $cargoInfo;
    }

    public function getOrderInfo(): OrderInfo
    {
        return $this->orderInfo;
    }

    public function setOrderInfo(OrderInfo $orderInfo): void
    {
        $this->orderInfo = $orderInfo;
    }

    public function getShopInfo(): ShopInfo
    {
        return $this->shopInfo;
    }

    public function setShopInfo(ShopInfo $shopInfo): void
    {
        $this->shopInfo = $shopInfo;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getRequestData(): ?array
    {
        return $this->requestData;
    }

    /**
     * @param array<string, mixed>|null $requestData
     */
    public function setRequestData(?array $requestData): void
    {
        $this->requestData = $requestData;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getResponseData(): ?array
    {
        return $this->responseData;
    }

    /**
     * @param array<mixed, mixed>|null $responseData
     */
    public function setResponseData(?array $responseData): void
    {
        $this->responseData = $responseData;
    }

    /**
     * 转换为创建订单请求参数
     *
     * @return array<string, mixed>
     */
    public function toRequestArray(): array
    {
        $request = [
            'delivery_id' => $this->getDeliveryCompanyId(),
            'shopid' => $this->getBindAccountId(),
            'shop_order_id' => $this->getOrderInfo()->getPoiSeq(),
            'sender' => $this->getSenderInfo()->toRequestArray(),
            'receiver' => $this->getReceiverInfo()->toRequestArray(),
            'cargo' => $this->getCargoInfo()->toRequestArray(),
            'order_info' => $this->getOrderInfo()->toRequestArray(),
            'shop_no_order' => 0,
        ];

        $shopInfo = $this->getShopInfo()->toRequestArray();
        if ([] !== $shopInfo) {
            $request['shop'] = $shopInfo;
        }

        return array_filter($request, fn ($value) => null !== $value && [] !== $value);
    }

    /**
     * 从响应数据更新订单状态
     *
     * @param array<mixed, mixed> $response
     */
    public function updateFromResponse(array $response): self
    {
        $this->setFeeFromResponse($response);
        $this->setWechatOrderIdFromResponse($response);
        $this->setDeliveryIdFromResponse($response);
        $this->setStatusFromResponse($response);

        return $this;
    }

    /**
     * @param array<mixed, mixed> $response
     */
    private function setFeeFromResponse(array $response): void
    {
        if (isset($response['fee'])) {
            $this->setFee(is_scalar($response['fee']) ? (string) $response['fee'] : null);
        }
    }

    /**
     * @param array<mixed, mixed> $response
     */
    private function setWechatOrderIdFromResponse(array $response): void
    {
        if (isset($response['order_id'])) {
            $this->setWechatOrderId(is_scalar($response['order_id']) ? (string) $response['order_id'] : null);
        }
    }

    /**
     * @param array<mixed, mixed> $response
     */
    private function setDeliveryIdFromResponse(array $response): void
    {
        if (isset($response['delivery_id'])) {
            $this->setDeliveryId(is_scalar($response['delivery_id']) ? (string) $response['delivery_id'] : null);
        }
    }

    /**
     * @param array<mixed, mixed> $response
     */
    private function setStatusFromResponse(array $response): void
    {
        if (isset($response['status'])) {
            $this->setStatus(is_scalar($response['status']) ? (string) $response['status'] : null);
        }
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
