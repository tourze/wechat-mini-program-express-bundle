<?php

namespace WechatMiniProgramExpressBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\Arrayable\ApiArrayInterface;
use Tourze\Arrayable\Arrayable;
use Tourze\Arrayable\PlainArrayInterface;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineIpBundle\Attribute\CreateIpColumn;
use Tourze\DoctrineIpBundle\Attribute\UpdateIpColumn;
use Tourze\DoctrineTimestampBundle\Attribute\CreateTimeColumn;
use Tourze\DoctrineTimestampBundle\Attribute\UpdateTimeColumn;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;
use Tourze\DoctrineUserBundle\Attribute\CreatedByColumn;
use Tourze\DoctrineUserBundle\Attribute\UpdatedByColumn;
use Tourze\EasyAdmin\Attribute\Action\Creatable;
use Tourze\EasyAdmin\Attribute\Action\Deletable;
use Tourze\EasyAdmin\Attribute\Action\Editable;
use Tourze\EasyAdmin\Attribute\Action\Listable;
use Tourze\EasyAdmin\Attribute\Column\BoolColumn;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Field\FormField;
use Tourze\EasyAdmin\Attribute\Filter\Filterable;
use Tourze\EasyAdmin\Attribute\Filter\Keyword;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramExpressBundle\Repository\BindAccountRepository;

#[AsPermission(title: '即时配送绑定账号')]
#[Listable]
#[Deletable]
#[Editable]
#[Creatable]
#[ORM\Entity(repositoryClass: BindAccountRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_express_bind_account', options: ['comment' => '即时配送绑定账号'])]
class BindAccount implements \Stringable, Arrayable, PlainArrayInterface, ApiArrayInterface, AdminArrayInterface
{
    #[ListColumn(order: -1)]
    #[ExportColumn]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[BoolColumn]
    #[IndexColumn]
    #[TrackColumn]
    #[Groups(['admin_curd', 'restful_read', 'restful_read', 'restful_write'])]
    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '有效', 'default' => 0])]
    #[ListColumn(order: 97)]
    #[FormField(order: 97)]
    private ?bool $valid = false;

    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(name: 'account_id', referencedColumnName: 'id', onDelete: 'CASCADE', options: ['comment' => '微信小程序账号'])]
    private ?Account $account = null;

    #[Groups(['admin_curd'])]
    #[TrackColumn]
    #[FormField(span: 18)]
    #[Keyword]
    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 64, options: ['comment' => '配送公司ID'])]
    private ?string $deliveryId = null;

    #[Groups(['admin_curd'])]
    #[TrackColumn]
    #[FormField(span: 18)]
    #[Keyword]
    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 64, options: ['comment' => '配送公司名称'])]
    private ?string $deliveryName = null;

    #[Groups(['admin_curd'])]
    #[TrackColumn]
    #[FormField(span: 18)]
    #[Keyword]
    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 64, options: ['comment' => '商户ID'])]
    private ?string $shopId = null;

    #[Groups(['admin_curd'])]
    #[TrackColumn]
    #[FormField(span: 18)]
    #[Keyword]
    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 64, nullable: true, options: ['comment' => '微信商户号'])]
    private ?string $shopNo = null;

    #[Groups(['admin_curd'])]
    #[TrackColumn]
    #[FormField(span: 18)]
    #[Keyword]
    #[ORM\Column(type: Types::STRING, length: 128, nullable: true, options: ['comment' => '商户秘钥'])]
    private ?string $appSecret = null;

    #[Groups(['admin_curd'])]
    #[TrackColumn]
    #[FormField(span: 18)]
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '额外配置'])]
    private array $extraConfig = [];

    #[CreateIpColumn]
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '创建时IP'])]
    private ?string $createdFromIp = null;

    #[UpdateIpColumn]
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '更新时IP'])]
    private ?string $updatedFromIp = null;

    #[CreatedByColumn]
    #[Groups(['restful_read'])]
    #[ORM\Column(nullable: true, options: ['comment' => '创建人'])]
    private ?string $createdBy = null;

    #[UpdatedByColumn]
    #[Groups(['restful_read'])]
    #[ORM\Column(nullable: true, options: ['comment' => '更新人'])]
    private ?string $updatedBy = null;

    #[Filterable]
    #[IndexColumn]
    #[ListColumn(order: 98, sorter: true)]
    #[ExportColumn]
    #[CreateTimeColumn]
    #[Groups(['restful_read', 'admin_curd', 'restful_read'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '创建时间'])]
    private ?\DateTimeInterface $createTime = null;

    #[UpdateTimeColumn]
    #[ListColumn(order: 99, sorter: true)]
    #[Groups(['restful_read', 'admin_curd', 'restful_read'])]
    #[Filterable]
    #[ExportColumn]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '更新时间'])]
    private ?\DateTimeInterface $updateTime = null;

    public function __toString(): string
    {
        if (!$this->getId()) {
            return '';
        }

        return "{$this->getDeliveryName()}({$this->getShopId()})";
    }

    public function setCreatedBy(?string $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setUpdatedBy(?string $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function getUpdatedBy(): ?string
    {
        return $this->updatedBy;
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
        if ($this->getAccount()) {
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
    }

    public function setCreateTime(?\DateTimeInterface $createdAt): void
    {
        $this->createTime = $createdAt;
    }

    public function getCreateTime(): ?\DateTimeInterface
    {
        return $this->createTime;
    }

    public function setUpdateTime(?\DateTimeInterface $updateTime): void
    {
        $this->updateTime = $updateTime;
    }

    public function getUpdateTime(): ?\DateTimeInterface
    {
        return $this->updateTime;
    }
}
