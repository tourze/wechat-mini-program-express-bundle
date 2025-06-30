<?php

namespace WechatMiniProgramExpressBundle\Tests\Integration\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramExpressBundle\Command\SyncBindAccountsCommand;
use WechatMiniProgramExpressBundle\Service\DeliveryConfigService;

class SyncBindAccountsCommandTest extends TestCase
{
    public function testCommandCanBeInstantiated(): void
    {
        $configService = $this->createMock(DeliveryConfigService::class);
        $accountRepository = $this->createMock(AccountRepository::class);
        
        $command = new SyncBindAccountsCommand($configService, $accountRepository);
        $this->assertInstanceOf(SyncBindAccountsCommand::class, $command);
    }

    public function testCommandHasCorrectName(): void
    {
        $configService = $this->createMock(DeliveryConfigService::class);
        $accountRepository = $this->createMock(AccountRepository::class);
        
        $command = new SyncBindAccountsCommand($configService, $accountRepository);
        $this->assertEquals('wechat-express:sync-bind-accounts', $command->getName());
    }

    public function testCommandCanBeExecuted(): void
    {
        $configService = $this->createMock(DeliveryConfigService::class);
        $accountRepository = $this->createMock(AccountRepository::class);
        $accountRepository->method('findBy')->willReturn([]);
        
        $application = new Application();
        $command = new SyncBindAccountsCommand($configService, $accountRepository);
        $application->add($command);

        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $this->assertEquals(0, $commandTester->getStatusCode());
    }
}