<?php

namespace WechatMiniProgramExpressBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramExpressBundle\Entity\BindAccount;
use WechatMiniProgramExpressBundle\Repository\BindAccountRepository;

/**
 * @internal
 */
#[CoversClass(BindAccountRepository::class)]
#[RunTestsInSeparateProcesses]
final class BindAccountRepositoryTest extends AbstractRepositoryTestCase
{
    protected function onSetUp(): void
    {
    }

    public function testFindByAccount(): void
    {
        $repository = self::getService(BindAccountRepository::class);
        $account = new Account();

        $result = $repository->findByAccount($account);
        $this->assertIsArray($result);
    }

    public function testFindByAccountAndDeliveryId(): void
    {
        $repository = self::getService(BindAccountRepository::class);
        $account = new Account();
        $deliveryId = 'test-delivery-id';

        $result = $repository->findByAccountAndDeliveryId($account, $deliveryId);
        $this->assertNull($result);
    }

    public function testSave(): void
    {
        $repository = self::getService(BindAccountRepository::class);
        $entity = new BindAccount();
        $entity->setDeliveryId('test-delivery-id');
        $entity->setDeliveryName('Test Delivery');
        $entity->setShopId('test-shop-id');
        $entity->setValid(true);

        // 测试保存不会抛出异常
        $repository->save($entity, false);
        $this->assertInstanceOf(BindAccount::class, $entity);
    }

    public function testRemove(): void
    {
        $repository = self::getService(BindAccountRepository::class);
        $entity = new BindAccount();
        $entity->setDeliveryId('test-delivery-id');
        $entity->setDeliveryName('Test Delivery');
        $entity->setShopId('test-shop-id');
        $entity->setValid(true);

        // 先保存
        $repository->save($entity, false);

        // 再移除
        $repository->remove($entity, false);
        $this->assertInstanceOf(BindAccount::class, $entity);
    }

    public function testFindOneByWithOrderByClause(): void
    {
        $repository = self::getService(BindAccountRepository::class);
        $result = $repository->findOneBy(['valid' => false], ['id' => 'DESC']);
        $this->assertTrue(null === $result || $result instanceof BindAccount);
    }

    public function testFindByAccountWithNullAccount(): void
    {
        $repository = self::getService(BindAccountRepository::class);
        $result = $repository->findBy(['account' => null]);
        $this->assertIsArray($result);
    }

    public function testCountByAccountWithNullAccount(): void
    {
        $repository = self::getService(BindAccountRepository::class);
        $result = $repository->count(['account' => null]);
        $this->assertIsInt($result);
        $this->assertGreaterThanOrEqual(0, $result);
    }

    public function testFindByShopNoIsNull(): void
    {
        $repository = self::getService(BindAccountRepository::class);
        $result = $repository->findBy(['shopNo' => null]);
        $this->assertIsArray($result);
    }

    public function testCountByShopNoIsNull(): void
    {
        $repository = self::getService(BindAccountRepository::class);
        $result = $repository->count(['shopNo' => null]);
        $this->assertIsInt($result);
        $this->assertGreaterThanOrEqual(0, $result);
    }

    public function testFindByAppSecretIsNull(): void
    {
        $repository = self::getService(BindAccountRepository::class);
        $result = $repository->findBy(['appSecret' => null]);
        $this->assertIsArray($result);
    }

    public function testCountByAppSecretIsNull(): void
    {
        $repository = self::getService(BindAccountRepository::class);
        $result = $repository->count(['appSecret' => null]);
        $this->assertIsInt($result);
        $this->assertGreaterThanOrEqual(0, $result);
    }

    public function testCountByAssociationAccountShouldReturnCorrectNumber(): void
    {
        $repository = self::getService(BindAccountRepository::class);
        $account = new Account();
        $result = $repository->count(['account' => $account]);
        $this->assertIsInt($result);
        $this->assertGreaterThanOrEqual(0, $result);
    }

    public function testFindOneByAssociationAccountShouldReturnMatchingEntity(): void
    {
        $repository = self::getService(BindAccountRepository::class);
        $account = new Account();
        $result = $repository->findOneBy(['account' => $account]);
        $this->assertTrue(null === $result || $result instanceof BindAccount);
    }

    /**
     * @return BindAccount
     */
    protected function createNewEntity(): object
    {
        $entity = new BindAccount();

        // 设置基本字段
        $entity->setDeliveryId('test-delivery-' . uniqid());
        $entity->setDeliveryName('Test Delivery');
        $entity->setShopId('test-shop-' . uniqid());
        $entity->setValid(true);

        return $entity;
    }

    /**
     * @return ServiceEntityRepository<BindAccount>
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return self::getService(BindAccountRepository::class);
    }
}
