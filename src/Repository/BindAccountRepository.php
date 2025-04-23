<?php

namespace WechatMiniProgramExpressBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineEnhanceBundle\Repository\CommonRepositoryAware;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramExpressBundle\Entity\BindAccount;

/**
 * 即时配送绑定账号仓库
 *
 * @method BindAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method BindAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method BindAccount[]    findAll()
 * @method BindAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BindAccountRepository extends ServiceEntityRepository
{
    use CommonRepositoryAware;

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
}
