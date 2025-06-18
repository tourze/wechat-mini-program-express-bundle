<?php

namespace WechatMiniProgramExpressBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineTimestampBundle\Attribute\UpdateTimeColumn;
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
class Order implements Stringable
{
    use TimestampableAware;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '订单ID'])]
    private ?int $id = null;

    /**
     * 微信 侧订单ID
     */
    #[TrackColumn]
    private ?string $wechatOrderId = null;

    /**
     * 配送单号
     */
    #[TrackColumn]
    private ?string $deliveryId = null;

    /**
     * 配送状态
     */
    #[TrackColumn]
    private ?string $status = null;

    /**
     * 配送费用（单位：元）
     */
    #[TrackColumn]
    private ?string $fee = null;

    /**
     * 配送公司ID
     */
    #[TrackColumn]
    private ?string $deliveryCompanyId = null;

    /**
     * 账号绑定ID
     */
    #[TrackColumn]
    private ?string $bindAccountId = null;

    /**
     * 发送方信息
     */
    #[ORM\Embedded(class: SenderInfo::class)]
    private SenderInfo $senderInfo;

    /**
     * 接收方信息
     */
    #[ORM\Embedded(class: ReceiverInfo::class)]
    private ReceiverInfo $receiverInfo;

    /**
     * 货物信息
     */
    #[ORM\Embedded(class: CargoInfo::class)]
    private CargoInfo $cargoInfo;

    /**
     * 订单信息
     */
    #[ORM\Embedded(class: OrderInfo::class)]
    private OrderInfo $orderInfo;

    /**
     * 商品信息
     */
    #[ORM\Embedded(class: ShopInfo::class)]
    private ShopInfo $shopInfo;

    /**
     * 原始请求数据
     */
    #[TrackColumn]
    private ?array $requestData = null;

    /**
     * 原始响应数据
     */
    #[TrackColumn]
    private ?array $responseData = null;

    #[IndexColumn]
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['comment' => '创建时间'])]/**
     * @DateRangePickerField()
     */
    #[UpdateTimeColumn]
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

    public function setWechatOrderId(?string $wechatOrderId): self
    {
        $this->wechatOrderId = $wechatOrderId;

        return $this;
    }

    public function getDeliveryId(): ?string
    {
        return $this->deliveryId;
    }

    public function setDeliveryId(?string $deliveryId): self
    {
        $this->deliveryId = $deliveryId;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getFee(): ?string
    {
        return $this->fee;
    }

    public function setFee(?string $fee): self
    {
        $this->fee = $fee;

        return $this;
    }

    public function getDeliveryCompanyId(): ?string
    {
        return $this->deliveryCompanyId;
    }

    public function setDeliveryCompanyId(?string $deliveryCompanyId): self
    {
        $this->deliveryCompanyId = $deliveryCompanyId;

        return $this;
    }

    public function getBindAccountId(): ?string
    {
        return $this->bindAccountId;
    }

    public function setBindAccountId(?string $bindAccountId): self
    {
        $this->bindAccountId = $bindAccountId;

        return $this;
    }

    public function getSenderInfo(): SenderInfo
    {
        return $this->senderInfo;
    }

    public function setSenderInfo(SenderInfo $senderInfo): self
    {
        $this->senderInfo = $senderInfo;

        return $this;
    }

    public function getReceiverInfo(): ReceiverInfo
    {
        return $this->receiverInfo;
    }

    public function setReceiverInfo(ReceiverInfo $receiverInfo): self
    {
        $this->receiverInfo = $receiverInfo;

        return $this;
    }

    public function getCargoInfo(): CargoInfo
    {
        return $this->cargoInfo;
    }

    public function setCargoInfo(CargoInfo $cargoInfo): self
    {
        $this->cargoInfo = $cargoInfo;

        return $this;
    }

    public function getOrderInfo(): OrderInfo
    {
        return $this->orderInfo;
    }

    public function setOrderInfo(OrderInfo $orderInfo): self
    {
        $this->orderInfo = $orderInfo;

        return $this;
    }

    public function getShopInfo(): ShopInfo
    {
        return $this->shopInfo;
    }

    public function setShopInfo(ShopInfo $shopInfo): self
    {
        $this->shopInfo = $shopInfo;

        return $this;
    }

    public function getRequestData(): ?array
    {
        return $this->requestData;
    }

    public function setRequestData(?array $requestData): self
    {
        $this->requestData = $requestData;

        return $this;
    }

    public function getResponseData(): ?array
    {
        return $this->responseData;
    }

    public function setResponseData(?array $responseData): self
    {
        $this->responseData = $responseData;

        return $this;
    }/**
     * 转换为创建订单请求参数
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
        if (!empty($shopInfo)) {
            $request['shop'] = $shopInfo;
        }

        return array_filter($request, fn ($value) => null !== $value && [] !== $value);
    }

    /**
     * 从响应数据更新订单状态
     */
    public function updateFromResponse(array $response): self
    {
        if ((bool) isset($response['fee'])) {
            $this->setFee((string) $response['fee']);
        }

        if ((bool) isset($response['order_id'])) {
            $this->setWechatOrderId($response['order_id']);
        }

        if ((bool) isset($response['delivery_id'])) {
            $this->setDeliveryId($response['delivery_id']);
        }

        if ((bool) isset($response['status'])) {
            $this->setStatus($response['status']);
        }

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
