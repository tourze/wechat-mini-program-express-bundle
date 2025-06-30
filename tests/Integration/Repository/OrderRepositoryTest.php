<?php

namespace WechatMiniProgramExpressBundle\Tests\Integration\Repository;

use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Repository\OrderRepository;

class OrderRepositoryTest extends TestCase
{
    public function testRepositoryCanBeInstantiated(): void
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $repository = new OrderRepository($managerRegistry);
        
        $this->assertInstanceOf(OrderRepository::class, $repository);
    }
}