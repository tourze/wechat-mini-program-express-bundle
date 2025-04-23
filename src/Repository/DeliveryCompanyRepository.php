<?php

namespace WechatMiniProgramExpressBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramExpressBundle\Entity\DeliveryCompany;

/**
 * 即时配送公司仓库
 *
 * @method DeliveryCompany|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeliveryCompany|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeliveryCompany[]    findAll()
 * @method DeliveryCompany[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeliveryCompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeliveryCompany::class);
    }

    /**
     * 根据deliveryId查找配送公司
     */
    public function findByDeliveryId(string $deliveryId): ?DeliveryCompany
    {
        return $this->findOneBy(['deliveryId' => $deliveryId]);
    }
}
