<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramExpressBundle\Entity\DeliveryCompany;

/**
 * 即时配送公司仓库
 *
 * @extends ServiceEntityRepository<DeliveryCompany>
 */
#[AsRepository(entityClass: DeliveryCompany::class)]
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

    public function save(DeliveryCompany $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DeliveryCompany $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
