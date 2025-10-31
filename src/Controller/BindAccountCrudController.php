<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use WechatMiniProgramExpressBundle\Entity\BindAccount;

#[AdminCrud(routePath: '/wechat-mini-program-express/bind-account', routeName: 'wechat_mini_program_express_bind_account')]
final class BindAccountCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return BindAccount::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('绑定账户')
            ->setEntityLabelInPlural('绑定账户')
            ->setSearchFields(['deliveryName', 'shopId', 'shopNo'])
            ->setDefaultSort(['id' => 'DESC'])
            ->setPaginatorPageSize(20)
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                return $action->setIcon('fa fa-eye')->setLabel('查看');
            })
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->hideOnForm()
            ->setHelp('系统自动生成的唯一标识符')
        ;

        yield BooleanField::new('valid', '是否有效')
            ->setHelp('标识该绑定账户是否处于有效状态')
        ;

        yield AssociationField::new('account', '微信小程序账号')
            ->setHelp('关联的微信小程序账号')
            ->setRequired(true)
        ;

        yield TextField::new('deliveryId', '配送公司ID')
            ->setHelp('配送公司的唯一标识符')
            ->setRequired(true)
            ->setMaxLength(255)
        ;

        yield TextField::new('deliveryName', '配送公司名称')
            ->setHelp('配送公司的显示名称')
            ->setRequired(true)
            ->setMaxLength(255)
        ;

        yield TextField::new('shopId', '商户ID')
            ->setHelp('商户的唯一标识符')
            ->setRequired(true)
            ->setMaxLength(255)
        ;

        yield TextField::new('shopNo', '商户编号')
            ->setHelp('商户的编号，可选字段')
            ->setMaxLength(255)
        ;

        yield TextField::new('appSecret', '商户秘钥')
            ->setHelp('用于API调用的秘钥信息')
            ->setMaxLength(128)
            ->hideOnIndex()
        ;

        yield TextareaField::new('extraConfig', '额外配置')
            ->setHelp('JSON格式的额外配置信息')
            ->hideOnIndex()
            ->hideOnForm()
            ->hideOnDetail()
            ->formatValue(function ($value) {
                return is_array($value) ? json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $value;
            })
        ;

        yield DateTimeField::new('createTime', '创建时间')
            ->hideOnForm()
            ->setFormat('yyyy-MM-dd HH:mm:ss')
            ->setHelp('记录创建的时间')
        ;

        yield DateTimeField::new('updateTime', '更新时间')
            ->hideOnForm()
            ->setFormat('yyyy-MM-dd HH:mm:ss')
            ->setHelp('记录最后更新的时间')
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('valid')
            ->add('deliveryName')
            ->add('shopId')
            ->add('createTime')
        ;
    }
}
