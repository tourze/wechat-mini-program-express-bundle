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
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramExpressBundle\Repository\BindAccountRepository;

/**
 * @implements ApiArrayInterface<string, mixed>
 * @implements AdminArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: BindAccountRepository::class)]
#[ORM\Table(name: 'wechat_mini_program_express_bind_account', options: ['comment' => '即时配送绑定账号'])]
class BindAccount implements \Stringable, ApiArrayInterface, AdminArrayInterface
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

    #[TrackColumn]
    #[ORM\Column(type: Types::BOOLEAN, nullable: false, options: ['comment' => '是否有效', 'default' => false])]
    #[Assert\NotNull]
    #[Assert\Type(type: 'bool')]
    private ?bool $valid = false;

    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(name: 'account_id', referencedColumnName: 'id', onDelete: 'CASCADE', options: ['comment' => '微信小程序账号'])]
    private ?Account $account = null;

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

    #[Groups(groups: ['admin_curd'])]
    #[TrackColumn]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: false, options: ['comment' => '商户ID'])]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'string')]
    #[Assert\Length(max: 255)]
    private ?string $shopId = null;

    #[Groups(groups: ['admin_curd'])]
    #[TrackColumn]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '商户编号'])]
    #[Assert\Type(type: 'string')]
    #[Assert\Length(max: 255)]
    private ?string $shopNo = null;

    #[Groups(groups: ['admin_curd'])]
    #[TrackColumn]
    #[ORM\Column(type: Types::STRING, length: 128, nullable: true, options: ['comment' => '商户秘钥'])]
    #[Assert\Type(type: 'string')]
    #[Assert\Length(max: 128)]
    private ?string $appSecret = null;

    /** @var array<string, mixed> */
    #[Groups(groups: ['admin_curd'])]
    #[TrackColumn]
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '额外配置'])]
    #[Assert\Type(type: 'array')]
    private array $extraConfig = [];

    public function __toString(): string
    {
        if (0 === $this->getId()) {
            return '';
        }

        return "{$this->getDeliveryName()}({$this->getShopId()})";
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(?bool $valid): void
    {
        $this->valid = $valid;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): void
    {
        $this->account = $account;
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

    public function getShopId(): ?string
    {
        return $this->shopId;
    }

    public function setShopId(string $shopId): void
    {
        $this->shopId = $shopId;
    }

    public function getShopNo(): ?string
    {
        return $this->shopNo;
    }

    public function setShopNo(?string $shopNo): void
    {
        $this->shopNo = $shopNo;
    }

    public function getAppSecret(): ?string
    {
        return $this->appSecret;
    }

    public function setAppSecret(?string $appSecret): void
    {
        $this->appSecret = $appSecret;
    }

    /**
     * @return array<string, mixed>
     */
    public function getExtraConfig(): array
    {
        return $this->extraConfig;
    }

    /**
     * @param array<string, mixed> $extraConfig
     */
    public function setExtraConfig(array $extraConfig): void
    {
        $this->extraConfig = $extraConfig;
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
            'valid' => $this->isValid(),
            'deliveryId' => $this->getDeliveryId(),
            'deliveryName' => $this->getDeliveryName(),
            'shopId' => $this->getShopId(),
            'shopNo' => $this->getShopNo(),
            'appSecret' => $this->getAppSecret(),
            'extraConfig' => $this->getExtraConfig(),
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
            'shopId' => $this->getShopId(),
            'shopNo' => $this->getShopNo(),
            'extraConfig' => $this->getExtraConfig(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function retrieveAdminArray(): array
    {
        $data = [
            'id' => $this->getId(),
            'deliveryId' => $this->getDeliveryId(),
            'deliveryName' => $this->getDeliveryName(),
            'shopId' => $this->getShopId(),
            'shopNo' => $this->getShopNo(),
            'extraConfig' => $this->getExtraConfig(),
        ];

        if (null !== $this->getAccount()) {
            $data['account'] = $this->getAccount()->retrieveAdminArray();
        }

        return $data;
    }
}
