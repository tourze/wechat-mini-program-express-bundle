<?php

namespace WechatMiniProgramExpressBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
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
#[Autoconfigure(lazy: true, public: true)]
class DeliveryConfigService
{
    public function __construct(
        private readonly Client $client,
        private readonly EntityManagerInterface $entityManager,
        private readonly DeliveryCompanyRepository $deliveryCompanyRepository,
        private readonly BindAccountRepository $bindAccountRepository,
        private readonly LoggerInterface $logger,
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

            if (!isset($response['list']) || !is_array($response['list'])) {
                throw new WechatExpressException('获取配送公司列表失败: 接口返回数据格式异常');
            }

            $companies = [];
            foreach ($response['list'] as $item) {
                $deliveryId = $item['delivery_id'] ?? null;
                $deliveryName = $item['delivery_name'] ?? null;

                if ($deliveryId === null || $deliveryName === null) {
                    continue;
                }

                // 检查是否已存在
                $company = $this->deliveryCompanyRepository->findByDeliveryId($deliveryId);
                if ($company === null) {
                    $company = new DeliveryCompany();
                    $company->setDeliveryId($deliveryId);
                }

                $company->setDeliveryName($deliveryName);
                $this->entityManager->persist($company);
                $companies[] = $company;
            }

            if (!empty($companies)) {
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

            if (!isset($response['shop_list']) || !is_array($response['shop_list'])) {
                throw new WechatExpressException('获取绑定账号失败: 接口返回数据格式异常');
            }

            $bindAccounts = [];
            foreach ($response['shop_list'] as $item) {
                $deliveryId = $item['delivery_id'] ?? null;
                $shopId = $item['shopid'] ?? null;

                if ($deliveryId === null || $shopId === null) {
                    continue;
                }

                // 获取配送公司
                $company = $this->deliveryCompanyRepository->findByDeliveryId($deliveryId);
                if ($company === null) {
                    // 如果配送公司不存在，尝试获取配送公司列表
                    $this->getAllDeliveryCompanies($account);
                    $company = $this->deliveryCompanyRepository->findByDeliveryId($deliveryId);

                    // 如果还是找不到，创建一个临时的
                    if ($company === null) {
                        $company = new DeliveryCompany();
                        $company->setDeliveryId($deliveryId);
                        $company->setDeliveryName($item['delivery_name'] ?? '未知配送公司');
                        $this->entityManager->persist($company);
                    }
                }

                // 检查绑定账号是否已存在
                $bindAccount = $this->bindAccountRepository->findByAccountAndDeliveryId($account, $deliveryId);
                if ($bindAccount === null) {
                    $bindAccount = new BindAccount();
                    $bindAccount->setAccount($account);
                    $bindAccount->setDeliveryId($deliveryId);
                }

                $bindAccount->setDeliveryName($company->getDeliveryName());
                $bindAccount->setShopId($shopId);
                $bindAccount->setShopNo($item['shop_no'] ?? null);

                // 提取额外配置
                $extraConfig = [];
                if ((bool) isset($item['delivery_service'])) {
                    $extraConfig['delivery_service'] = $item['delivery_service'];
                }
                if ((bool) isset($item['audit_result'])) {
                    $extraConfig['audit_result'] = $item['audit_result'];
                }
                if (!empty($extraConfig)) {
                    $bindAccount->setExtraConfig($extraConfig);
                }

                $this->entityManager->persist($bindAccount);
                $bindAccounts[] = $bindAccount;
            }

            if (!empty($bindAccounts)) {
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
}
