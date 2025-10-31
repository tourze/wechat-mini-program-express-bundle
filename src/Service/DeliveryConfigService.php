<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramExpressBundle\Entity\BindAccount;
use WechatMiniProgramExpressBundle\Entity\DeliveryCompany;
use WechatMiniProgramExpressBundle\Exception\WechatExpressException;
use WechatMiniProgramExpressBundle\Repository\BindAccountRepository;
use WechatMiniProgramExpressBundle\Repository\DeliveryCompanyRepository;
use WechatMiniProgramExpressBundle\Request\GetAllImmeDeliveryRequest;
use WechatMiniProgramExpressBundle\Request\GetBindAccountRequest;

/**
 * 微信小程序即时配送配置服务
 *
 * 负责处理配送公司和绑定账号等配置信息
 */
#[Autoconfigure(public: true)]
#[WithMonologChannel(channel: 'wechat_mini_program_express')]
readonly class DeliveryConfigService
{
    public function __construct(
        private Client $client,
        private EntityManagerInterface $entityManager,
        private DeliveryCompanyRepository $deliveryCompanyRepository,
        private BindAccountRepository $bindAccountRepository,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * 获取已支持的配送公司列表
     *
     * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/getAllImmeDelivery.html
     *
     * @return DeliveryCompany[] 配送公司列表
     */
    public function getAllDeliveryCompanies(Account $account): array
    {
        try {
            $request = new GetAllImmeDeliveryRequest();
            $request->setAccount($account);

            $response = $this->client->request($request);

            if (!is_array($response) || !isset($response['list']) || !is_array($response['list'])) {
                throw new WechatExpressException('获取配送公司列表失败: 接口返回数据格式异常');
            }

            $companies = [];
            foreach ($response['list'] as $item) {
                if (!is_array($item)) {
                    continue;
                }

                $deliveryId = $item['delivery_id'] ?? null;
                $deliveryName = $item['delivery_name'] ?? null;

                if (null === $deliveryId || null === $deliveryName || !is_scalar($deliveryId) || !is_scalar($deliveryName)) {
                    continue;
                }

                $deliveryIdString = (string) $deliveryId;
                $deliveryNameString = (string) $deliveryName;

                // 检查是否已存在
                $company = $this->deliveryCompanyRepository->findByDeliveryId($deliveryIdString);
                if (null === $company) {
                    $company = new DeliveryCompany();
                    $company->setDeliveryId($deliveryIdString);
                }

                $company->setDeliveryName($deliveryNameString);
                $this->entityManager->persist($company);
                $companies[] = $company;
            }

            if ([] !== $companies) {
                $this->entityManager->flush();
            }

            return $companies;
        } catch (\Throwable $exception) {
            $this->logger->error('获取配送公司列表失败', [
                'exception' => $exception,
                'account' => $account,
            ]);
            throw $exception;
        }
    }

    /**
     * 拉取已绑定账号
     *
     * @see https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/getBindAccount.html
     *
     * @return BindAccount[] 绑定账号列表
     */
    public function getBindAccounts(Account $account): array
    {
        try {
            $request = new GetBindAccountRequest();
            $request->setAccount($account);

            $response = $this->client->request($request);

            if (!is_array($response) || !isset($response['shop_list']) || !is_array($response['shop_list'])) {
                throw new WechatExpressException('获取绑定账号失败: 接口返回数据格式异常');
            }

            $shopList = [];
            foreach ($response['shop_list'] as $key => $item) {
                if (is_array($item)) {
                    $shopList[$key] = $item;
                }
            }
            $bindAccounts = $this->processShopList($account, $shopList);

            if ([] !== $bindAccounts) {
                $this->entityManager->flush();
            }

            return $bindAccounts;
        } catch (\Throwable $exception) {
            $this->logger->error('获取绑定账号失败', [
                'exception' => $exception,
                'account' => $account,
            ]);
            throw $exception;
        }
    }

    /**
     * 处理商店列表
     *
     * @param array<mixed, mixed> $shopList
     * @return array<BindAccount>
     */
    private function processShopList(Account $account, array $shopList): array
    {
        $bindAccounts = [];
        foreach ($shopList as $item) {
            if (!is_array($item)) {
                continue;
            }
            $bindAccount = $this->processShopItem($account, $item);
            if (null !== $bindAccount) {
                $bindAccounts[] = $bindAccount;
            }
        }

        return $bindAccounts;
    }

    /**
     * 处理单个商店项目
     *
     * @param array<mixed, mixed> $item
     */
    private function processShopItem(Account $account, array $item): ?BindAccount
    {
        $deliveryId = $item['delivery_id'] ?? null;
        $shopId = $item['shopid'] ?? null;

        if (null === $deliveryId || null === $shopId || !is_scalar($deliveryId) || !is_scalar($shopId)) {
            return null;
        }

        $company = $this->getOrCreateDeliveryCompany($account, (string) $deliveryId, $item);
        $bindAccount = $this->getOrCreateBindAccount($account, (string) $deliveryId);

        $this->updateBindAccountFromItem($bindAccount, $company, $item);

        $this->entityManager->persist($bindAccount);

        return $bindAccount;
    }

    /**
     * 获取或创建配送公司
     *
     * @param array<mixed, mixed> $item
     */
    private function getOrCreateDeliveryCompany(Account $account, string $deliveryId, array $item): DeliveryCompany
    {
        $company = $this->deliveryCompanyRepository->findByDeliveryId($deliveryId);

        if (null === $company) {
            $this->getAllDeliveryCompanies($account);
            $company = $this->deliveryCompanyRepository->findByDeliveryId($deliveryId);

            if (null === $company) {
                $company = new DeliveryCompany();
                $company->setDeliveryId($deliveryId);
                $deliveryName = $item['delivery_name'] ?? null;
                $company->setDeliveryName(is_scalar($deliveryName) ? (string) $deliveryName : '未知配送公司');
                $this->entityManager->persist($company);
            }
        }

        return $company;
    }

    /**
     * 获取或创建绑定账号
     */
    private function getOrCreateBindAccount(Account $account, string $deliveryId): BindAccount
    {
        $bindAccount = $this->bindAccountRepository->findByAccountAndDeliveryId($account, $deliveryId);

        if (null === $bindAccount) {
            $bindAccount = new BindAccount();
            $bindAccount->setAccount($account);
            $bindAccount->setDeliveryId($deliveryId);
        }

        return $bindAccount;
    }

    /**
     * 从项目数据更新绑定账号
     *
     * @param array<mixed, mixed> $item
     */
    private function updateBindAccountFromItem(BindAccount $bindAccount, DeliveryCompany $company, array $item): void
    {
        $deliveryName = $company->getDeliveryName();
        if (null !== $deliveryName) {
            $bindAccount->setDeliveryName($deliveryName);
        }

        $shopId = $item['shopid'] ?? null;
        if (is_scalar($shopId)) {
            $bindAccount->setShopId((string) $shopId);
        }

        $shopNo = $item['shop_no'] ?? null;
        $bindAccount->setShopNo(is_scalar($shopNo) ? (string) $shopNo : null);

        $extraConfig = $this->extractExtraConfig($item);
        if ([] !== $extraConfig) {
            $bindAccount->setExtraConfig($extraConfig);
        }
    }

    /**
     * 提取额外配置
     *
     * @param array<mixed, mixed> $item
     * @return array<string, mixed>
     */
    private function extractExtraConfig(array $item): array
    {
        $extraConfig = [];

        if (isset($item['delivery_service'])) {
            $extraConfig['delivery_service'] = $item['delivery_service'];
        }

        if (isset($item['audit_result'])) {
            $extraConfig['audit_result'] = $item['audit_result'];
        }

        return $extraConfig;
    }
}
