<?php

namespace WechatMiniProgramExpressBundle\Tests\Service;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramBundle\Service\Client;
use WechatMiniProgramExpressBundle\Entity\BindAccount;
use WechatMiniProgramExpressBundle\Entity\DeliveryCompany;
use WechatMiniProgramExpressBundle\Exception\WechatExpressException;
use WechatMiniProgramExpressBundle\Repository\BindAccountRepository;
use WechatMiniProgramExpressBundle\Repository\DeliveryCompanyRepository;
use WechatMiniProgramExpressBundle\Service\DeliveryConfigService;

class DeliveryConfigServiceTest extends TestCase
{
    private Client $client;
    private EntityManagerInterface $entityManager;
    private DeliveryCompanyRepository $deliveryCompanyRepository;
    private BindAccountRepository $bindAccountRepository;
    private LoggerInterface $logger;
    private DeliveryConfigService $service;
    private Account $account;

    protected function setUp(): void
    {
        $this->client = $this->createMock(Client::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->deliveryCompanyRepository = $this->createMock(DeliveryCompanyRepository::class);
        $this->bindAccountRepository = $this->createMock(BindAccountRepository::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->account = $this->createMock(Account::class);

        $this->service = new DeliveryConfigService(
            $this->client,
            $this->entityManager,
            $this->deliveryCompanyRepository,
            $this->bindAccountRepository,
            $this->logger
        );
    }

    public function testGetAllDeliveryCompanies_Success(): void
    {
        // 准备测试数据
        $responseData = [
            'list' => [
                [
                    'delivery_id' => 'delivery1',
                    'delivery_name' => '配送公司1'
                ],
                [
                    'delivery_id' => 'delivery2',
                    'delivery_name' => '配送公司2'
                ]
            ]
        ];

        // 配置模拟行为
        $this->client->expects($this->once())
            ->method('request')
            ->willReturn($responseData);

        $this->deliveryCompanyRepository->expects($this->exactly(2))
            ->method('findByDeliveryId')
            ->willReturn(null); // 假设配送公司不存在，需要创建新的

        $this->entityManager->expects($this->atLeastOnce())
            ->method('persist');
        $this->entityManager->expects($this->once())
            ->method('flush');

        // 执行测试
        $companies = $this->service->getAllDeliveryCompanies($this->account);

        // 断言结果
        $this->assertCount(2, $companies);
        $this->assertInstanceOf(DeliveryCompany::class, $companies[0]);
        $this->assertInstanceOf(DeliveryCompany::class, $companies[1]);
        $this->assertEquals('delivery1', $companies[0]->getDeliveryId());
        $this->assertEquals('配送公司1', $companies[0]->getDeliveryName());
        $this->assertEquals('delivery2', $companies[1]->getDeliveryId());
        $this->assertEquals('配送公司2', $companies[1]->getDeliveryName());
    }

    public function testGetAllDeliveryCompanies_EmptyResponse(): void
    {
        // 准备测试数据：空响应
        $responseData = ['list' => []];

        // 配置模拟行为
        $this->client->expects($this->once())
            ->method('request')
            ->willReturn($responseData);

        $this->entityManager->expects($this->never())
            ->method('flush');

        // 执行测试
        $companies = $this->service->getAllDeliveryCompanies($this->account);

        // 断言结果
        $this->assertIsArray($companies);
        $this->assertEmpty($companies);
    }

    public function testGetAllDeliveryCompanies_InvalidResponse(): void
    {
        // 准备测试数据：无效响应
        $responseData = ['error' => 'Some error']; // 没有 list 字段

        // 配置模拟行为
        $this->client->expects($this->once())
            ->method('request')
            ->willReturn($responseData);

        $this->expectException(WechatExpressException::class);
        $this->expectExceptionMessage('获取配送公司列表失败');

        // 执行测试
        $this->service->getAllDeliveryCompanies($this->account);
    }

    public function testGetAllDeliveryCompanies_ApiError(): void
    {
        // 配置模拟行为：API抛出异常
        $this->client->expects($this->once())
            ->method('request')
            ->willThrowException(new \Exception('API错误'));

        $this->logger->expects($this->once())
            ->method('error')
            ->with(
                $this->equalTo('获取配送公司列表失败'),
                $this->callback(function ($context) {
                    return isset($context['exception']) && isset($context['account']);
                })
            );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('API错误');

        // 执行测试
        $this->service->getAllDeliveryCompanies($this->account);
    }

    public function testGetBindAccounts_Success(): void
    {
        // 准备测试数据
        $responseData = [
            'shop_list' => [
                [
                    'delivery_id' => 'delivery1',
                    'delivery_name' => '配送公司1',
                    'shopid' => 'shop1',
                    'shop_no' => 'no1',
                    'delivery_service' => 1,
                    'audit_result' => 2
                ],
                [
                    'delivery_id' => 'delivery2',
                    'shopid' => 'shop2'
                ]
            ]
        ];

        // 创建配送公司mock
        $company1 = new DeliveryCompany();
        $company1->setDeliveryId('delivery1');
        $company1->setDeliveryName('配送公司1');

        $company2 = new DeliveryCompany();
        $company2->setDeliveryId('delivery2');
        $company2->setDeliveryName('配送公司2');

        // 配置模拟行为
        $this->client->expects($this->once())
            ->method('request')
            ->willReturn($responseData);

        $this->deliveryCompanyRepository->expects($this->exactly(2))
            ->method('findByDeliveryId')
            ->willReturnMap([
                ['delivery1', $company1],
                ['delivery2', $company2]
            ]);

        $this->bindAccountRepository->expects($this->exactly(2))
            ->method('findByAccountAndDeliveryId')
            ->willReturn(null); // 假设绑定账号不存在，需要创建新的

        $this->entityManager->expects($this->atLeastOnce())
            ->method('persist');
        $this->entityManager->expects($this->once())
            ->method('flush');

        // 执行测试
        $bindAccounts = $this->service->getBindAccounts($this->account);

        // 断言结果
        $this->assertCount(2, $bindAccounts);
        $this->assertInstanceOf(BindAccount::class, $bindAccounts[0]);
        $this->assertInstanceOf(BindAccount::class, $bindAccounts[1]);
        $this->assertEquals('delivery1', $bindAccounts[0]->getDeliveryId());
        $this->assertEquals('配送公司1', $bindAccounts[0]->getDeliveryName());
        $this->assertEquals('shop1', $bindAccounts[0]->getShopId());
        $this->assertEquals('no1', $bindAccounts[0]->getShopNo());
        $this->assertEquals('delivery2', $bindAccounts[1]->getDeliveryId());
        $this->assertEquals('配送公司2', $bindAccounts[1]->getDeliveryName());
        $this->assertEquals('shop2', $bindAccounts[1]->getShopId());
        
        // 检查extraConfig
        $extraConfig = $bindAccounts[0]->getExtraConfig();
        $this->assertArrayHasKey('delivery_service', $extraConfig);
        $this->assertArrayHasKey('audit_result', $extraConfig);
        $this->assertEquals(1, $extraConfig['delivery_service']);
        $this->assertEquals(2, $extraConfig['audit_result']);
    }

    public function testGetBindAccounts_EmptyResponse(): void
    {
        // 准备测试数据：空响应
        $responseData = ['shop_list' => []];

        // 配置模拟行为
        $this->client->expects($this->once())
            ->method('request')
            ->willReturn($responseData);

        $this->entityManager->expects($this->never())
            ->method('flush');

        // 执行测试
        $bindAccounts = $this->service->getBindAccounts($this->account);

        // 断言结果
        $this->assertIsArray($bindAccounts);
        $this->assertEmpty($bindAccounts);
    }

    public function testGetBindAccounts_InvalidResponse(): void
    {
        // 准备测试数据：无效响应
        $responseData = ['error' => 'Some error']; // 没有 shop_list 字段

        // 配置模拟行为
        $this->client->expects($this->once())
            ->method('request')
            ->willReturn($responseData);

        $this->expectException(WechatExpressException::class);
        $this->expectExceptionMessage('获取绑定账号失败');

        // 执行测试
        $this->service->getBindAccounts($this->account);
    }

    public function testGetBindAccounts_ApiError(): void
    {
        // 配置模拟行为：API抛出异常
        $this->client->expects($this->once())
            ->method('request')
            ->willThrowException(new \Exception('API错误'));

        $this->logger->expects($this->once())
            ->method('error')
            ->with(
                $this->equalTo('获取绑定账号失败'),
                $this->callback(function ($context) {
                    return isset($context['exception']) && isset($context['account']);
                })
            );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('API错误');

        // 执行测试
        $this->service->getBindAccounts($this->account);
    }
} 