<?php

namespace WechatMiniProgramExpressBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatMiniProgramExpressBundle\Entity\Order;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
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
        return $this->createQueryBuilder('o')
            ->join('o.orderInfo', 'oi')
            ->where('oi.poiSeq = :storeOrderId')
            ->setParameter('storeOrderId', $storeOrderId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
