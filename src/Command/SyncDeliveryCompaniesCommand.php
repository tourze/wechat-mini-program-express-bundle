<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Repository\AccountRepository;
use WechatMiniProgramExpressBundle\Entity\DeliveryCompany;
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
            ->addOption('account-id', null, InputOption::VALUE_OPTIONAL, '指定微信小程序账号ID')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $accountId = $input->getOption('account-id');
        $accounts = $this->getAccountsToSync($io, is_string($accountId) ? $accountId : null);

        if (null === $accounts) {
            return Command::FAILURE;
        }

        if ([] === $accounts) {
            $io->warning('没有可用的微信小程序账号');

            return Command::SUCCESS;
        }

        $io->title('开始同步微信小程序即时配送公司');

        $result = $this->syncAccountsDeliveryCompanies($io, $accounts);

        $this->displaySyncResult($io, $accounts, $result);

        return $this->determineFinalResult($result, count($accounts));
    }

    /**
     * 获取要同步的账号列表
     *
     * @return array<int, Account>|null
     */
    private function getAccountsToSync(SymfonyStyle $io, ?string $accountId): ?array
    {
        if (null !== $accountId) {
            $account = $this->accountRepository->find($accountId);
            if (null === $account) {
                $io->error("找不到ID为 {$accountId} 的微信小程序账号");

                return null;
            }

            return [$account];
        }

        return $this->accountRepository->findBy(['valid' => true]);
    }

    /**
     * 同步账号配送公司信息
     *
     * @param array<int, Account> $accounts
     * @return array{totalCompanies: int, errors: array<int, array{account: string, message: string}>}
     */
    private function syncAccountsDeliveryCompanies(SymfonyStyle $io, array $accounts): array
    {
        $totalCompanies = 0;
        $errors = [];

        foreach ($accounts as $account) {
            $result = $this->syncSingleAccountDeliveryCompanies($io, $account);

            $totalCompanies += $result['count'];

            if (null !== $result['error']) {
                $errors[] = $result['error'];
            }
        }

        return [
            'totalCompanies' => $totalCompanies,
            'errors' => $errors,
        ];
    }

    /**
     * 同步单个账号的配送公司信息
     *
     * @return array{count: int, error: array{account: string, message: string}|null}
     */
    private function syncSingleAccountDeliveryCompanies(SymfonyStyle $io, Account $account): array
    {
        try {
            $io->section("正在同步账号: {$account->getName()} ({$account->getAppId()})");

            $companies = $this->configService->getAllDeliveryCompanies($account);

            $io->success(sprintf('成功同步 %d 个配送公司', count($companies)));

            $this->displayDeliveryCompanies($io, $companies);

            return ['count' => count($companies), 'error' => null];
        } catch (\Throwable $e) {
            $error = [
                'account' => $account->getName() ?? 'Unknown',
                'message' => $e->getMessage(),
            ];
            $io->error("同步失败: {$e->getMessage()}");

            return ['count' => 0, 'error' => $error];
        }
    }

    /**
     * 显示配送公司详情
     *
     * @param array<int, DeliveryCompany> $companies
     */
    private function displayDeliveryCompanies(SymfonyStyle $io, array $companies): void
    {
        foreach ($companies as $company) {
            $io->writeln(" - {$company->getDeliveryName()} ({$company->getDeliveryId()})");
        }
    }

    /**
     * 显示同步结果
     *
     * @param array<int, Account> $accounts
     * @param array{totalCompanies: int, errors: array<int, array{account: string, message: string}>} $result
     */
    private function displaySyncResult(SymfonyStyle $io, array $accounts, array $result): void
    {
        $io->newLine();
        $io->section('同步结果摘要');
        $io->writeln('总账号数: ' . count($accounts));
        $io->writeln('成功同步账号数: ' . (count($accounts) - count($result['errors'])));
        $io->writeln('同步失败账号数: ' . count($result['errors']));
        $io->writeln('总配送公司数: ' . $result['totalCompanies']);

        if ([] !== $result['errors']) {
            $io->section('同步失败详情');
            foreach ($result['errors'] as $error) {
                $io->writeln(" - {$error['account']}: {$error['message']}");
            }
        }
    }

    /**
     * 确定最终返回结果
     *
     * @param array{totalCompanies: int, errors: array<int, array{account: string, message: string}>} $result
     */
    private function determineFinalResult(array $result, int $totalAccounts): int
    {
        if ([] === $result['errors']) {
            return Command::SUCCESS;
        }

        return count($result['errors']) === $totalAccounts
            ? Command::FAILURE
            : Command::SUCCESS;
    }
}
