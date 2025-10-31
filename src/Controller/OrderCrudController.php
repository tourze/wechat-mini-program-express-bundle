<?php

declare(strict_types=1);

namespace WechatMiniProgramExpressBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Tourze\EasyAdminEnumFieldBundle\Field\EnumField;
use WechatMiniProgramExpressBundle\Entity\Order;
use WechatMiniProgramExpressBundle\Enum\OrderStatus;

#[AdminCrud(routePath: '/wechat-mini-program-express/order', routeName: 'wechat_mini_program_express_order')]
final class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('配送订单')
            ->setEntityLabelInPlural('配送订单')
            ->setSearchFields(['wechatOrderId', 'deliveryId', 'status'])
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

        yield TextField::new('wechatOrderId', '微信订单ID')
            ->setHelp('微信侧生成的订单唯一标识符')
            ->setMaxLength(255)
        ;

        yield TextField::new('deliveryId', '配送单号')
            ->setHelp('配送公司生成的配送单号')
            ->setMaxLength(255)
        ;

        $field = EnumField::new('status', '订单状态');
        $field->setEnumCases(OrderStatus::cases());
        $field->setHelp('当前订单的配送状态');
        yield $field;

        yield TextField::new('fee', '配送费用')
            ->setHelp('配送费用（单位：元）')
            ->setMaxLength(255)
        ;

        yield TextField::new('deliveryCompanyId', '配送公司ID')
            ->setHelp('负责配送的公司标识符')
            ->setMaxLength(255)
        ;

        yield TextField::new('bindAccountId', '绑定账户ID')
            ->setHelp('关联的账户绑定标识符')
            ->setMaxLength(255)
        ;

        // 发送方信息
        yield TextField::new('senderInfo.name', '发送方姓名')
            ->setHelp('发送方联系人姓名')
            ->hideOnIndex()
            ->hideOnDetail()
            ->hideWhenUpdating()
        ;

        yield TextField::new('senderInfo.phone', '发送方电话')
            ->setHelp('发送方联系电话')
            ->hideOnIndex()
            ->hideOnDetail()
            ->hideWhenUpdating()
        ;

        yield TextField::new('senderInfo.address', '发送方地址')
            ->setHelp('发送方详细地址')
            ->hideOnIndex()
            ->hideOnDetail()
            ->hideWhenUpdating()
        ;

        // 接收方信息
        yield TextField::new('receiverInfo.name', '接收方姓名')
            ->setHelp('接收方联系人姓名')
            ->hideOnIndex()
            ->hideOnDetail()
            ->hideWhenUpdating()
        ;

        yield TextField::new('receiverInfo.phone', '接收方电话')
            ->setHelp('接收方联系电话')
            ->hideOnIndex()
            ->hideOnDetail()
            ->hideWhenUpdating()
        ;

        yield TextField::new('receiverInfo.address', '接收方地址')
            ->setHelp('接收方详细地址')
            ->hideOnIndex()
            ->hideOnDetail()
            ->hideWhenUpdating()
        ;

        // 货物信息
        yield TextareaField::new('cargoInfo.goodsValue', '货物价值')
            ->setHelp('货物总价值')
            ->hideOnIndex()
            ->hideOnDetail()
            ->hideWhenUpdating()
            ->formatValue(function ($value) {
                return is_array($value) || is_object($value) ? json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : (string) $value;
            })
        ;

        yield TextareaField::new('cargoInfo.goodsWeight', '货物重量')
            ->setHelp('货物总重量')
            ->hideOnIndex()
            ->hideOnDetail()
            ->hideWhenUpdating()
            ->formatValue(function ($value) {
                return is_array($value) || is_object($value) ? json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : (string) $value;
            })
        ;

        yield TextareaField::new('cargoInfo.goodsDetail', '货物详情')
            ->setHelp('货物详细描述')
            ->hideOnIndex()
            ->hideOnDetail()
            ->hideWhenUpdating()
            ->formatValue(function ($value) {
                return is_array($value) || is_object($value) ? json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : (string) $value;
            })
        ;

        // 订单信息
        yield TextField::new('orderInfo.deliveryServiceCode', '服务类型')
            ->setHelp('配送服务类型代码')
            ->hideOnIndex()
            ->hideOnDetail()
            ->hideWhenUpdating()
        ;

        yield TextField::new('orderInfo.orderTime', '下单时间')
            ->setHelp('订单创建时间')
            ->hideOnIndex()
            ->hideOnDetail()
            ->hideWhenUpdating()
        ;

        yield TextField::new('orderInfo.expectedDeliveryTime', '期望送达时间')
            ->setHelp('客户期望的送达时间')
            ->hideOnIndex()
            ->hideOnDetail()
            ->hideWhenUpdating()
        ;

        yield TextField::new('orderInfo.poiSeq', '门店订单号')
            ->setHelp('门店内部订单编号')
            ->hideOnIndex()
            ->hideOnDetail()
            ->hideWhenUpdating()
        ;

        // 商品信息
        yield TextField::new('shopInfo.wcPoi', '微信门店ID')
            ->setHelp('微信门店标识符')
            ->hideOnIndex()
            ->hideOnDetail()
            ->hideWhenUpdating()
        ;

        yield TextField::new('shopInfo.shopOrderId', '门店订单ID')
            ->setHelp('门店系统订单ID')
            ->hideOnIndex()
            ->hideOnDetail()
            ->hideWhenUpdating()
        ;

        yield TextField::new('shopInfo.deliverySign', '配送标识')
            ->setHelp('配送相关标识信息')
            ->hideOnIndex()
            ->hideOnDetail()
            ->hideWhenUpdating()
        ;

        yield TextareaField::new('requestData', '请求数据')
            ->setHelp('原始API请求数据（JSON格式）')
            ->hideOnIndex()
            ->formatValue(function ($value) {
                return is_array($value) ? json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $value;
            })
        ;

        yield TextareaField::new('responseData', '响应数据')
            ->setHelp('API响应数据（JSON格式）')
            ->hideOnIndex()
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
            ->add('status')
            ->add('wechatOrderId')
            ->add('deliveryId')
            ->add('deliveryCompanyId')
            ->add('bindAccountId')
            ->add('createTime')
        ;
    }
}
