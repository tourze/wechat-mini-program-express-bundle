<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\Arrayable\ApiArrayInterface;
use Tourze\DoctrineIpBundle\Traits\IpTraceableAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use WechatMiniProgramExpressBundle\Repository\DeliveryCompanyRepository;

/**
 * @implements ApiArrayInterface<string, mixed>
 * @implements AdminArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: DeliveryCompanyRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_express_delivery_company', options: ['comment' => '即时配送公司'])]
class DeliveryCompany implements \Stringable, ApiArrayInterface, AdminArrayInterface
{
    use TimestampableAware;
    use BlameableAware;
    use IpTraceableAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private int $id = 0;

    public function getId(): int
    {
        return $this->id;
    }

    #[Groups(groups: ['admin_curd'])]
    #[TrackColumn]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: false, options: ['comment' => '配送公司ID'])]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'string')]
    #[Assert\Length(max: 255)]
    private ?string $deliveryId = null;

    #[Groups(groups: ['admin_curd'])]
    #[TrackColumn]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: false, options: ['comment' => '配送公司名称'])]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'string')]
    #[Assert\Length(max: 255)]
    private ?string $deliveryName = null;

    #[TrackColumn]
    #[ORM\Column(type: Types::BOOLEAN, nullable: false, options: ['comment' => '是否有效', 'default' => false])]
    #[Assert\NotNull]
    #[Assert\Type(type: 'bool')]
    private ?bool $valid = false;

    public function __toString(): string
    {
        if (0 === $this->getId()) {
            return '';
        }

        return "{$this->getDeliveryName()}({$this->getDeliveryId()})";
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(?bool $valid): void
    {
        $this->valid = $valid;
    }

    public function getDeliveryId(): ?string
    {
        return $this->deliveryId;
    }

    public function setDeliveryId(string $deliveryId): void
    {
        $this->deliveryId = $deliveryId;
    }

    public function getDeliveryName(): ?string
    {
        return $this->deliveryName;
    }

    public function setDeliveryName(string $deliveryName): void
    {
        $this->deliveryName = $deliveryName;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->retrievePlainArray();
    }

    /**
     * @return array<string, mixed>
     */
    public function retrievePlainArray(): array
    {
        return [
            'id' => $this->getId(),
            'deliveryId' => $this->getDeliveryId(),
            'deliveryName' => $this->getDeliveryName(),
            'valid' => $this->isValid(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function retrieveApiArray(): array
    {
        return [
            'id' => $this->getId(),
            'deliveryId' => $this->getDeliveryId(),
            'deliveryName' => $this->getDeliveryName(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function retrieveAdminArray(): array
    {
        return [
            'id' => $this->getId(),
            'deliveryId' => $this->getDeliveryId(),
            'deliveryName' => $this->getDeliveryName(),
        ];
    }
}
