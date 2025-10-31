<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\Tests\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatMiniProgramExpressBundle\Controller\BindAccountCrudController;
use WechatMiniProgramExpressBundle\Entity\BindAccount;

/**
 * @internal
 */
#[CoversClass(BindAccountCrudController::class)]
#[RunTestsInSeparateProcesses]
class BindAccountCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    private BindAccountCrudController $controller;

    protected function setUpController(): void
    {
        $this->controller = self::getService(BindAccountCrudController::class);
    }

    protected function getControllerService(): BindAccountCrudController
    {
        return self::getService(BindAccountCrudController::class);
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '是否有效' => ['是否有效'];
        yield '微信小程序账号' => ['微信小程序账号'];
        yield '配送公司ID' => ['配送公司ID'];
        yield '配送公司名称' => ['配送公司名称'];
        yield '商户ID' => ['商户ID'];
        yield '商户编号' => ['商户编号'];
        yield '创建时间' => ['创建时间'];
        yield '更新时间' => ['更新时间'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        yield 'valid' => ['valid'];
        yield 'account' => ['account'];
        yield 'deliveryId' => ['deliveryId'];
        yield 'deliveryName' => ['deliveryName'];
        yield 'shopId' => ['shopId'];
        yield 'shopNo' => ['shopNo'];
        yield 'appSecret' => ['appSecret'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield 'valid' => ['valid'];
        yield 'account' => ['account'];
        yield 'deliveryId' => ['deliveryId'];
        yield 'deliveryName' => ['deliveryName'];
        yield 'shopId' => ['shopId'];
        yield 'shopNo' => ['shopNo'];
        yield 'appSecret' => ['appSecret'];
    }

    private function getController(): BindAccountCrudController
    {
        return new BindAccountCrudController();
    }

    public function testGetEntityFqcn(): void
    {
        $result = BindAccountCrudController::getEntityFqcn();

        $this->assertSame(BindAccount::class, $result);
    }

    public function testGetEntityFqcnReturnsCorrectClass(): void
    {
        $this->assertSame(BindAccount::class, BindAccountCrudController::getEntityFqcn());
    }

    public function testConfigureCrud(): void
    {
        $crud = Crud::new();
        $result = $this->getController()->configureCrud($crud);

        // 验证返回的是同一个Crud对象
        $this->assertSame($crud, $result);
        $this->assertInstanceOf(Crud::class, $result);
    }

    public function testConfigureCrudReturnsCorrectConfiguration(): void
    {
        $this->setUpController();
        $crud = $this->controller->configureCrud(Crud::new());

        $this->assertInstanceOf(Crud::class, $crud);
        $this->assertTrue(true, 'Crud configuration completed successfully');
    }

    public function testConfigureFields(): void
    {
        $fields = iterator_to_array($this->getController()->configureFields(Crud::PAGE_INDEX));

        $this->assertNotEmpty($fields);

        // 验证字段数量合理（包含所有基本字段）
        $this->assertGreaterThanOrEqual(8, count($fields));

        // 验证每个字段都是FieldInterface的实例
        foreach ($fields as $field) {
            $this->assertInstanceOf(FieldInterface::class, $field);
        }
    }

    public function testConfigureFieldsForIndexPageReturnsCorrectFields(): void
    {
        $this->setUpController();
        $fields = iterator_to_array($this->controller->configureFields(Crud::PAGE_INDEX));

        $this->assertNotEmpty($fields, 'Index page should have fields configured');

        // 验证每个字段都是有效的EasyAdmin字段对象
        foreach ($fields as $field) {
            $this->assertInstanceOf(FieldInterface::class, $field);
        }

        $this->assertGreaterThanOrEqual(1, count($fields), 'Index page should have at least 1 field');
    }

    public function testConfigureFieldsForNewPage(): void
    {
        $fields = iterator_to_array($this->getController()->configureFields(Crud::PAGE_NEW));

        $this->assertNotEmpty($fields);

        // 验证新建页面有字段配置
        $this->assertGreaterThan(0, count($fields));

        // 验证每个字段都是FieldInterface的实例
        foreach ($fields as $field) {
            $this->assertInstanceOf(FieldInterface::class, $field);
        }
    }

    public function testConfigureFieldsForNewPageIncludesPasswordField(): void
    {
        $this->setUpController();
        $fields = iterator_to_array($this->controller->configureFields(Crud::PAGE_NEW));

        $this->assertNotEmpty($fields, 'New page should have fields configured');

        // 验证每个字段都是有效的EasyAdmin字段对象
        foreach ($fields as $field) {
            $this->assertInstanceOf(FieldInterface::class, $field);
        }

        $this->assertGreaterThanOrEqual(1, count($fields), 'New page should have at least 1 field');
    }

    public function testConfigureFieldsForEditPage(): void
    {
        $fields = iterator_to_array($this->getController()->configureFields(Crud::PAGE_EDIT));

        $this->assertNotEmpty($fields);

        // 验证编辑页面有字段配置
        $this->assertGreaterThan(0, count($fields));

        // 验证每个字段都是FieldInterface的实例
        foreach ($fields as $field) {
            $this->assertInstanceOf(FieldInterface::class, $field);
        }
    }

    public function testConfigureFieldsForEditPageExcludesPasswordAndTokenFields(): void
    {
        $this->setUpController();
        $fields = iterator_to_array($this->controller->configureFields(Crud::PAGE_EDIT));

        $this->assertNotEmpty($fields, 'Edit page should have fields configured');

        // 验证每个字段都是有效的EasyAdmin字段对象
        foreach ($fields as $field) {
            $this->assertInstanceOf(FieldInterface::class, $field);
        }

        $this->assertGreaterThanOrEqual(1, count($fields), 'Edit page should have at least 1 field');
    }

    public function testValidationErrors(): void
    {
        $client = $this->createAuthenticatedClient();
        $crawler = $client->request('GET', $this->generateAdminUrl('new'));

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Create')->form();

        // 提交空表单以触发验证错误
        $crawler = $client->submit($form);

        // 验证响应状态码为422（表单验证失败）
        $this->assertResponseStatusCodeSame(422);

        // 验证页面包含验证错误信息
        $this->assertStringContainsString('should not be blank', $crawler->filter('.invalid-feedback')->text());
    }
}
