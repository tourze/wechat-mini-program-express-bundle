<?php

namespace WechatMiniProgramExpressBundle\Tests\Integration\Repository;

use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Repository\BindAccountRepository;

class BindAccountRepositoryTest extends TestCase
{
    public function testRepositoryCanBeInstantiated(): void
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $repository = new BindAccountRepository($managerRegistry);
        
        $this->assertInstanceOf(BindAccountRepository::class, $repository);
    }
}