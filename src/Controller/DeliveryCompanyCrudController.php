<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use WechatMiniProgramExpressBundle\Entity\DeliveryCompany;

#[AdminCrud(routePath: '/wechat-mini-program-express/delivery-company', routeName: 'wechat_mini_program_express_delivery_company')]
final class DeliveryCompanyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DeliveryCompany::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('配送公司')
            ->setEntityLabelInPlural('配送公司')
            ->setSearchFields(['deliveryName', 'deliveryId'])
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

        yield BooleanField::new('valid', '是否有效')
            ->setHelp('标识该配送公司是否处于有效状态')
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
            ->add('deliveryId')
            ->add('createTime')
        ;
    }
}
