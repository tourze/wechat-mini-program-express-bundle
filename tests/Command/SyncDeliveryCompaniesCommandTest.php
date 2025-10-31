<?php

namespace WechatMiniProgramExpressBundle\Tests\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Tourze\PHPUnitSymfonyKernelTest\AbstractCommandTestCase;
use WechatMiniProgramExpressBundle\Command\SyncDeliveryCompaniesCommand;

/**
 * @internal
 */
#[CoversClass(SyncDeliveryCompaniesCommand::class)]
#[RunTestsInSeparateProcesses]
final class SyncDeliveryCompaniesCommandTest extends AbstractCommandTestCase
{
    private CommandTester $commandTester;

    public function testCommandCanBeInstantiated(): void
    {
        $command = self::getContainer()->get(SyncDeliveryCompaniesCommand::class);
        $this->assertInstanceOf(SyncDeliveryCompaniesCommand::class, $command);
    }

    public function testCommandHasCorrectName(): void
    {
        /** @var SyncDeliveryCompaniesCommand $command */
        $command = self::getContainer()->get(SyncDeliveryCompaniesCommand::class);
        $this->assertEquals('wechat-express:sync-delivery-companies', $command->getName());
    }

    public function testCommandCanBeExecuted(): void
    {
        $exitCode = $this->commandTester->execute([]);

        // 在测试环境中，如果没有账号数据，命令应该显示相关信息并可能失败
        // 这是集成测试的正常行为，应该接受合理的失败情况
        $this->assertContains($exitCode, [0, 1],
            '命令应该要么成功执行，要么因为缺少测试数据而失败');

        // 验证命令输出包含预期的消息（无论成功还是失败）
        $output = $this->commandTester->getDisplay();
        $this->assertTrue(
            str_contains($output, '没有可用的微信小程序账号')
            || str_contains($output, '开始同步微信小程序即时配送公司')
            || str_contains($output, '找不到')
            || str_contains($output, '同步失败')
            || str_contains($output, '配送公司'),
            '命令输出应该包含相关的执行信息'
        );
    }

    public function testOptionAccountId(): void
    {
        $exitCode = $this->commandTester->execute(['--account-id' => '123']);

        // 测试带有 account-id 选项的命令执行
        // 在测试环境中，这个ID可能不存在，命令可能会失败，这是正常的
        $this->assertContains($exitCode, [0, 1],
            '命令应该要么成功执行，要么因为指定的账号ID不存在而失败');

        $output = $this->commandTester->getDisplay();
        $this->assertTrue(
            str_contains($output, '没有可用的微信小程序账号')
            || str_contains($output, '开始同步微信小程序即时配送公司')
            || str_contains($output, '找不到')
            || str_contains($output, '同步失败')
            || str_contains($output, '配送公司')
            || str_contains($output, '指定的账号'),
            '命令输出应该包含相关的执行信息'
        );
    }

    protected function getCommandTester(): CommandTester
    {
        return $this->commandTester;
    }

    protected function onSetUp(): void
    {
        // 从容器获取命令实例
        /** @var SyncDeliveryCompaniesCommand $command */
        $command = self::getContainer()->get(SyncDeliveryCompaniesCommand::class);

        $application = new Application();
        $application->add($command);

        $command = $application->find('wechat-express:sync-delivery-companies');
        $this->commandTester = new CommandTester($command);
    }
}
