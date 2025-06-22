<?php

namespace WechatMiniProgramExpressBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramExpressBundle\Service\DeliveryConfigService;

/**
 * 同步微信小程序即时配送公司命令
 */
#[AsCommand(
    name: self::NAME,
    description: '同步微信小程序即时配送公司'
)]
class SyncDeliveryCompaniesCommand extends Command
{
    
    public const NAME = 'wechat-express:sync-delivery-companies';
public function __construct(
        private readonly DeliveryConfigService $configService,
        private readonly AccountRepository $accountRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('account-id', null, InputOption::VALUE_OPTIONAL, '指定微信小程序账号ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $accountId = $input->getOption('account-id');

        if ((bool) $accountId) {
            $account = $this->accountRepository->find($accountId);
            if ($account === null) {
                $io->error("找不到ID为 {$accountId} 的微信小程序账号");

                return Command::FAILURE;
            }

            $accounts = [$account];
        } else {
            $accounts = $this->accountRepository->findBy(['valid' => true]);
            if ((bool) empty($accounts)) {
                $io->warning('没有可用的微信小程序账号');

                return Command::SUCCESS;
            }
        }

        $io->title('开始同步微信小程序即时配送公司');

        $totalCompanies = 0;
        $errors = [];

        foreach ($accounts as $account) {
            try {
                $io->section("正在同步账号: {$account->getName()} ({$account->getAppId()})");

                $companies = $this->configService->getAllDeliveryCompanies($account);
                $totalCompanies += count($companies);

                $io->success(sprintf('成功同步 %d 个配送公司', count($companies)));

                foreach ($companies as $company) {
                    $io->writeln(" - {$company->getDeliveryName()} ({$company->getDeliveryId()})");
                }
            } catch (\Throwable $e) {
                $errors[] = [
                    'account' => $account->getName(),
                    'message' => $e->getMessage(),
                ];
                $io->error("同步失败: {$e->getMessage()}");
            }
        }

        $io->newLine();
        $io->section('同步结果摘要');
        $io->writeln('总账号数: ' . count($accounts));
        $io->writeln('成功同步账号数: ' . (count($accounts) - count($errors)));
        $io->writeln('同步失败账号数: ' . count($errors));
        $io->writeln('总配送公司数: ' . $totalCompanies);

        if (!empty($errors)) {
            $io->section('同步失败详情');
            foreach ($errors as $error) {
                $io->writeln(" - {$error['account']}: {$error['message']}");
            }

            return count($errors) == count($accounts)
                ? Command::FAILURE
                : Command::SUCCESS;
        }

        return Command::SUCCESS;
    }
}
