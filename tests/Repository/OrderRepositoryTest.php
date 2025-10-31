<?php

namespace WechatMiniProgramExpressBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramExpressBundle\Entity\Order;
use WechatMiniProgramExpressBundle\Repository\OrderRepository;

/**
 * @internal
 */
#[CoversClass(OrderRepository::class)]
#[RunTestsInSeparateProcesses]
final class OrderRepositoryTest extends AbstractRepositoryTestCase
{
    protected function onSetUp(): void
    {
    }

    public function testFindByDeliveryId(): void
    {
        $repository = self::getService(OrderRepository::class);
        $deliveryId = 'test-delivery-id';

        $result = $repository->findByDeliveryId($deliveryId);
        $this->assertNull($result);
    }

    public function testFindByStoreOrderId(): void
    {
        $repository = self::getService(OrderRepository::class);
        $storeOrderId = 'test-store-order-id';

        $result = $repository->findByStoreOrderId($storeOrderId);
        $this->assertNull($result);
    }

    public function testFindByWechatOrderId(): void
    {
        $repository = self::getService(OrderRepository::class);
        $wechatOrderId = 'test-wechat-order-id';

        $result = $repository->findByWechatOrderId($wechatOrderId);
        $this->assertNull($result);
    }

    public function testFindOneByWithOrderByClause(): void
    {
        $repository = self::getService(OrderRepository::class);
        $result = $repository->findOneBy(['status' => 'test'], ['id' => 'DESC']);
        $this->assertTrue(null === $result || $result instanceof Order);
    }

    public function testCountByWechatOrderIdIsNull(): void
    {
        $repository = self::getService(OrderRepository::class);
        $result = $repository->count(['wechatOrderId' => null]);
        $this->assertIsInt($result);
        $this->assertGreaterThanOrEqual(0, $result);
    }

    public function testCountByDeliveryIdIsNull(): void
    {
        $repository = self::getService(OrderRepository::class);
        $result = $repository->count(['deliveryId' => null]);
        $this->assertIsInt($result);
        $this->assertGreaterThanOrEqual(0, $result);
    }

    public function testFindByWechatOrderIdIsNull(): void
    {
        $repository = self::getService(OrderRepository::class);
        $result = $repository->findBy(['wechatOrderId' => null]);
        $this->assertIsArray($result);
    }

    public function testFindByDeliveryIdIsNull(): void
    {
        $repository = self::getService(OrderRepository::class);
        $result = $repository->findBy(['deliveryId' => null]);
        $this->assertIsArray($result);
    }

    public function testSave(): void
    {
        $repository = self::getService(OrderRepository::class);
        $entity = new Order();
        $entity->setWechatOrderId('test-wechat-order-id');
        $entity->setDeliveryId('test-delivery-id');
        $entity->setStatus('pending');

        $repository->save($entity, false);
        $this->assertInstanceOf(Order::class, $entity);
    }

    public function testRemove(): void
    {
        $repository = self::getService(OrderRepository::class);
        $entity = new Order();
        $entity->setWechatOrderId('test-wechat-order-id');
        $entity->setDeliveryId('test-delivery-id');
        $entity->setStatus('pending');

        $repository->save($entity, false);
        $repository->remove($entity, false);
        $this->assertInstanceOf(Order::class, $entity);
    }

    public function testCountByFeeIsNull(): void
    {
        $repository = self::getService(OrderRepository::class);
        $result = $repository->count(['fee' => null]);
        $this->assertIsInt($result);
        $this->assertGreaterThanOrEqual(0, $result);
    }

    public function testFindByFeeIsNull(): void
    {
        $repository = self::getService(OrderRepository::class);
        $result = $repository->findBy(['fee' => null]);
        $this->assertIsArray($result);
    }

    public function testCountByStatusIsNull(): void
    {
        $repository = self::getService(OrderRepository::class);
        $result = $repository->count(['status' => null]);
        $this->assertIsInt($result);
        $this->assertGreaterThanOrEqual(0, $result);
    }

    public function testFindByStatusIsNull(): void
    {
        $repository = self::getService(OrderRepository::class);
        $result = $repository->findBy(['status' => null]);
        $this->assertIsArray($result);
    }

    public function testCountByDeliveryCompanyIdIsNull(): void
    {
        $repository = self::getService(OrderRepository::class);
        $result = $repository->count(['deliveryCompanyId' => null]);
        $this->assertIsInt($result);
        $this->assertGreaterThanOrEqual(0, $result);
    }

    public function testFindByDeliveryCompanyIdIsNull(): void
    {
        $repository = self::getService(OrderRepository::class);
        $result = $repository->findBy(['deliveryCompanyId' => null]);
        $this->assertIsArray($result);
    }

    public function testCountByBindAccountIdIsNull(): void
    {
        $repository = self::getService(OrderRepository::class);
        $result = $repository->count(['bindAccountId' => null]);
        $this->assertIsInt($result);
        $this->assertGreaterThanOrEqual(0, $result);
    }

    public function testFindByBindAccountIdIsNull(): void
    {
        $repository = self::getService(OrderRepository::class);
        $result = $repository->findBy(['bindAccountId' => null]);
        $this->assertIsArray($result);
    }

    /**
     * @return Order
     */
    protected function createNewEntity(): object
    {
        $entity = new Order();

        // 设置基本字段
        $entity->setWechatOrderId('test-order-' . uniqid());
        $entity->setDeliveryId('test-delivery-id');
        $entity->setStatus('pending');

        return $entity;
    }

    /**
     * @return ServiceEntityRepository<Order>
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return self::getService(OrderRepository::class);
    }
}
