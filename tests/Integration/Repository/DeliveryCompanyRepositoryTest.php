<?php

namespace WechatMiniProgramExpressBundle\Tests\Integration\Repository;

use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Repository\DeliveryCompanyRepository;

class DeliveryCompanyRepositoryTest extends TestCase
{
    public function testRepositoryCanBeInstantiated(): void
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $repository = new DeliveryCompanyRepository($managerRegistry);
        
        $this->assertInstanceOf(DeliveryCompanyRepository::class, $repository);
    }
}