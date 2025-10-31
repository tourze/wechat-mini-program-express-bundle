<?php

namespace WechatMiniProgramExpressBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramExpressBundle\Entity\DeliveryCompany;
use WechatMiniProgramExpressBundle\Repository\DeliveryCompanyRepository;

/**
 * @internal
 */
#[CoversClass(DeliveryCompanyRepository::class)]
#[RunTestsInSeparateProcesses]
final class DeliveryCompanyRepositoryTest extends AbstractRepositoryTestCase
{
    protected function onSetUp(): void
    {
    }

    public function testFindByDeliveryId(): void
    {
        $repository = self::getService(DeliveryCompanyRepository::class);
        $deliveryId = 'test-delivery-id';

        $result = $repository->findByDeliveryId($deliveryId);
        $this->assertNull($result);
    }

    public function testFindOneByWithOrderByClause(): void
    {
        $repository = self::getService(DeliveryCompanyRepository::class);
        $result = $repository->findOneBy(['valid' => false], ['id' => 'DESC']);
        $this->assertTrue(null === $result || $result instanceof DeliveryCompany);
    }

    public function testCountByDeliveryIdIsNull(): void
    {
        $repository = self::getService(DeliveryCompanyRepository::class);
        $result = $repository->count(['deliveryId' => null]);
        $this->assertIsInt($result);
        $this->assertGreaterThanOrEqual(0, $result);
    }

    public function testCountByDeliveryNameIsNull(): void
    {
        $repository = self::getService(DeliveryCompanyRepository::class);
        $result = $repository->count(['deliveryName' => null]);
        $this->assertIsInt($result);
        $this->assertGreaterThanOrEqual(0, $result);
    }

    public function testFindByDeliveryIdIsNull(): void
    {
        $repository = self::getService(DeliveryCompanyRepository::class);
        $result = $repository->findBy(['deliveryId' => null]);
        $this->assertIsArray($result);
    }

    public function testFindByDeliveryNameIsNull(): void
    {
        $repository = self::getService(DeliveryCompanyRepository::class);
        $result = $repository->findBy(['deliveryName' => null]);
        $this->assertIsArray($result);
    }

    public function testSave(): void
    {
        $repository = self::getService(DeliveryCompanyRepository::class);
        $entity = new DeliveryCompany();
        $entity->setDeliveryId('test-delivery-id');
        $entity->setDeliveryName('Test Delivery Company');
        $entity->setValid(true);

        $repository->save($entity, false);
        $this->assertInstanceOf(DeliveryCompany::class, $entity);
    }

    public function testRemove(): void
    {
        $repository = self::getService(DeliveryCompanyRepository::class);
        $entity = new DeliveryCompany();
        $entity->setDeliveryId('test-delivery-id');
        $entity->setDeliveryName('Test Delivery Company');
        $entity->setValid(true);

        $repository->save($entity, false);
        $repository->remove($entity, false);
        $this->assertInstanceOf(DeliveryCompany::class, $entity);
    }

    /**
     * @return DeliveryCompany
     */
    protected function createNewEntity(): object
    {
        $entity = new DeliveryCompany();

        // 设置基本字段
        $entity->setDeliveryId('test-delivery-' . uniqid());
        $entity->setDeliveryName('Test Delivery Company');

        return $entity;
    }

    /**
     * @return ServiceEntityRepository<DeliveryCompany>
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return self::getService(DeliveryCompanyRepository::class);
    }
}
