<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramExpressBundle\Entity\BindAccount;

/**
 * 即时配送绑定账号仓库
 *
 * @extends ServiceEntityRepository<BindAccount>
 */
#[AsRepository(entityClass: BindAccount::class)]
class BindAccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BindAccount::class);
    }

    /**
     * 根据微信小程序账号和配送公司ID查找绑定账号
     */
    public function findByAccountAndDeliveryId(Account $account, string $deliveryId): ?BindAccount
    {
        return $this->findOneBy([
            'account' => $account,
            'deliveryId' => $deliveryId,
        ]);
    }

    /**
     * 获取指定微信小程序账号的所有绑定账号
     *
     * @return BindAccount[]
     */
    public function findByAccount(Account $account): array
    {
        return $this->findBy(['account' => $account]);
    }

    public function save(BindAccount $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BindAccount $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
