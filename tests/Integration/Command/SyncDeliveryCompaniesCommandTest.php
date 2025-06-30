<?php

namespace WechatMiniProgramExpressBundle\Tests\Integration\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramExpressBundle\Command\SyncDeliveryCompaniesCommand;
use WechatMiniProgramExpressBundle\Service\DeliveryConfigService;

class SyncDeliveryCompaniesCommandTest extends TestCase
{
    public function testCommandCanBeInstantiated(): void
    {
        $configService = $this->createMock(DeliveryConfigService::class);
        $accountRepository = $this->createMock(AccountRepository::class);
        
        $command = new SyncDeliveryCompaniesCommand($configService, $accountRepository);
        $this->assertInstanceOf(SyncDeliveryCompaniesCommand::class, $command);
    }

    public function testCommandHasCorrectName(): void
    {
        $configService = $this->createMock(DeliveryConfigService::class);
        $accountRepository = $this->createMock(AccountRepository::class);
        
        $command = new SyncDeliveryCompaniesCommand($configService, $accountRepository);
        $this->assertEquals('wechat-express:sync-delivery-companies', $command->getName());
    }

    public function testCommandCanBeExecuted(): void
    {
        $configService = $this->createMock(DeliveryConfigService::class);
        $accountRepository = $this->createMock(AccountRepository::class);
        $accountRepository->method('findBy')->willReturn([]);
        
        $application = new Application();
        $command = new SyncDeliveryCompaniesCommand($configService, $accountRepository);
        $application->add($command);

        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $this->assertEquals(0, $commandTester->getStatusCode());
    }
}