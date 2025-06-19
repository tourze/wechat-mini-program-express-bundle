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
use Tourze\DoctrineUserBundle\Attribute\CreatedByColumn;
use Tourze\DoctrineUserBundle\Attribute\UpdatedByColumn;
use WechatMiniProgramExpressBundle\Repository\DeliveryCompanyRepository;

#[ORM\Entity(repositoryClass: DeliveryCompanyRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_express_delivery_company', options: ['comment' => '即时配送公司'])]
class DeliveryCompany implements \Stringable, Arrayable, PlainArrayInterface, ApiArrayInterface, AdminArrayInterface
{
    use TimestampableAware;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups(['admin_curd'])]
    #[TrackColumn]
    private ?string $deliveryId = null;

    #[Groups(['admin_curd'])]
    #[TrackColumn]
    private ?string $deliveryName = null;

    #[TrackColumn]
    private ?bool $valid = false;

    #[CreateIpColumn]
    private ?string $createdFromIp = null;

    #[UpdateIpColumn]
    private ?string $updatedFromIp = null;

    #[CreatedByColumn]
    private ?string $createdBy = null;

    #[UpdatedByColumn]
    private ?string $updatedBy = null;

    public function __toString(): string
    {
        if (!$this->getId()) {
            return '';
        }

        return "{$this->getDeliveryName()}({$this->getDeliveryId()})";
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

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'deliveryId' => $this->getDeliveryId(),
            'deliveryName' => $this->getDeliveryName(),
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
        return $this->toArray();
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
    }}
