<?php

namespace WechatMiniProgramExpressBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\Arrayable\ApiArrayInterface;
use Tourze\Arrayable\Arrayable;
use Tourze\Arrayable\PlainArrayInterface;
use Tourze\DoctrineIpBundle\Attribute\CreateIpColumn;
use Tourze\DoctrineIpBundle\Attribute\UpdateIpColumn;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramExpressBundle\Repository\BindAccountRepository;

#[ORM\Entity(repositoryClass: BindAccountRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_express_bind_account', options: ['comment' => '即时配送绑定账号'])]
class BindAccount implements \Stringable, Arrayable, PlainArrayInterface, ApiArrayInterface, AdminArrayInterface
{
    use TimestampableAware;
    use BlameableAware;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[TrackColumn]
    private ?bool $valid = false;

    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(name: 'account_id', referencedColumnName: 'id', onDelete: 'CASCADE', options: ['comment' => '微信小程序账号'])]
    private ?Account $account = null;

    #[Groups(groups: ['admin_curd'])]
    #[TrackColumn]
    private ?string $deliveryId = null;

    #[Groups(groups: ['admin_curd'])]
    #[TrackColumn]
    private ?string $deliveryName = null;

    #[Groups(groups: ['admin_curd'])]
    #[TrackColumn]
    private ?string $shopId = null;

    #[Groups(groups: ['admin_curd'])]
    #[TrackColumn]
    private ?string $shopNo = null;

    #[Groups(groups: ['admin_curd'])]
    #[TrackColumn]
    #[ORM\Column(type: Types::STRING, length: 128, nullable: true, options: ['comment' => '商户秘钥'])]
    private ?string $appSecret = null;

    #[Groups(groups: ['admin_curd'])]
    #[TrackColumn]
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '额外配置'])]
    private array $extraConfig = [];

    #[CreateIpColumn]
    private ?string $createdFromIp = null;

    #[UpdateIpColumn]
    private ?string $updatedFromIp = null;


    public function __toString(): string
    {
        if ($this->getId() === null || $this->getId() === 0) {
            return '';
        }

        return "{$this->getDeliveryName()}({$this->getShopId()})";
    }


    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(?bool $valid): self
    {
        $this->valid = $valid;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getDeliveryId(): ?string
    {
        return $this->deliveryId;
    }

    public function setDeliveryId(string $deliveryId): self
    {
        $this->deliveryId = $deliveryId;

        return $this;
    }

    public function getDeliveryName(): ?string
    {
        return $this->deliveryName;
    }

    public function setDeliveryName(string $deliveryName): self
    {
        $this->deliveryName = $deliveryName;

        return $this;
    }

    public function getShopId(): ?string
    {
        return $this->shopId;
    }

    public function setShopId(string $shopId): self
    {
        $this->shopId = $shopId;

        return $this;
    }

    public function getShopNo(): ?string
    {
        return $this->shopNo;
    }

    public function setShopNo(?string $shopNo): self
    {
        $this->shopNo = $shopNo;

        return $this;
    }

    public function getAppSecret(): ?string
    {
        return $this->appSecret;
    }

    public function setAppSecret(?string $appSecret): self
    {
        $this->appSecret = $appSecret;

        return $this;
    }

    public function getExtraConfig(): array
    {
        return $this->extraConfig;
    }

    public function setExtraConfig(array $extraConfig): self
    {
        $this->extraConfig = $extraConfig;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'deliveryId' => $this->getDeliveryId(),
            'deliveryName' => $this->getDeliveryName(),
            'shopId' => $this->getShopId(),
            'shopNo' => $this->getShopNo(),
            'extraConfig' => $this->getExtraConfig(),
        ];
    }

    public function retrievePlainArray(): array
    {
        return $this->toArray();
    }

    public function retrieveApiArray(): array
    {
        return $this->toArray();
    }

    public function retrieveAdminArray(): array
    {
        $data = $this->toArray();
        if ($this->getAccount() !== null) {
            $data['account'] = $this->getAccount()->retrieveAdminArray();
        }

        return $data;
    }

    public function setCreatedFromIp(?string $createdFromIp): self
    {
        $this->createdFromIp = $createdFromIp;

        return $this;
    }

    public function getCreatedFromIp(): ?string
    {
        return $this->createdFromIp;
    }

    public function setUpdatedFromIp(?string $updatedFromIp): self
    {
        $this->updatedFromIp = $updatedFromIp;

        return $this;
    }

    public function getUpdatedFromIp(): ?string
    {
        return $this->updatedFromIp;
    }}
