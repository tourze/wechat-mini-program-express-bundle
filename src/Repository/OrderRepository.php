<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatMiniProgramExpressBundle\Entity\Order;

/**
 * @extends ServiceEntityRepository<Order>
 */
#[AsRepository(entityClass: Order::class)]
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * 根据微信订单ID查找订单
     */
    public function findByWechatOrderId(string $orderId): ?Order
    {
        return $this->findOneBy(['wechatOrderId' => $orderId]);
    }

    /**
     * 根据配送单号查找订单
     */
    public function findByDeliveryId(string $deliveryId): ?Order
    {
        return $this->findOneBy(['deliveryId' => $deliveryId]);
    }

    /**
     * 根据门店订单流水号查找订单
     */
    public function findByStoreOrderId(string $storeOrderId): ?Order
    {
        $result = $this->createQueryBuilder('o')
            ->where('o.orderInfo.poiSeq = :storeOrderId')
            ->setParameter('storeOrderId', $storeOrderId)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $result instanceof Order ? $result : null;
    }

    public function save(Order $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Order $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
