# 微信小程序即时配送组件

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.1-blue)](https://www.php.net)
[![License](https://img.shields.io/badge/license-MIT-green)](../../LICENSE)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen)](#)
[![Code Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen)](#)

[English](README.md) | [中文](README.zh-CN.md)

微信小程序即时配送API集成组件，提供与微信小程序即时配送平台交互的能力，支持美团、闪送等多家配送服务提供商。

## 目录

- [功能特性](#功能特性)
- [安装](#安装)
- [配置](#配置)
- [使用方法](#使用方法)
- [高级用法](#高级用法)
- [技术架构](#技术架构)
- [命令行工具](#命令行工具)
- [API 参考](#api-参考)
- [安全注意事项](#安全注意事项)
- [依赖关系](#依赖关系)
- [贡献](#贡献)
- [许可证](#许可证)

## 功能特性

- **配送公司管理**: 同步和管理支持的配送服务提供商
- **账号绑定**: 管理小程序与配送公司的绑定关系
- **订单操作**: 支持完整的订单生命周期管理
- **查询功能**: 实时查询订单状态和配送信息
- **测试支持**: 提供模拟环境用于开发测试
- **命令行工具**: 提供便捷的数据同步命令

## 安装

```bash
composer require tourze/wechat-mini-program-express-bundle
```

如果使用Symfony Flex，Bundle会自动注册。否则，手动添加到 `config/bundles.php`：

```php
return [
    // ... 其他bundles
    WechatMiniProgramExpressBundle\WechatMiniProgramExpressBundle::class => ['all' => true],
];
```

## 配置

Bundle无需额外配置，依赖于 `WechatMiniProgramBundle` 的配置。确保已正确配置微信小程序账号信息。

示例配置文件 `config/packages/wechat_mini_program.yaml`：

```yaml
wechat_mini_program:
    default_account: 'your_default_account'
    accounts:
        your_account:
            app_id: 'your_app_id'
            secret: 'your_secret'
```

## 使用方法

### 基本用法

#### 1. 同步配送公司信息

```bash
# 同步所有配送公司信息
bin/console wechat-express:sync-delivery-companies

# 同步指定账号的配送公司信息
bin/console wechat-express:sync-delivery-companies --account-id=1
```

### 2. 同步绑定账号

```bash
# 同步所有绑定账号
bin/console wechat-express:sync-bind-accounts

# 同步指定账号的绑定信息
bin/console wechat-express:sync-bind-accounts --account-id=1
```

### 3. 下单流程

```php
use WechatMiniProgramExpressBundle\Service\DeliveryOrderService;
use WechatMiniProgramExpressBundle\Request\PreAddOrderRequest;
use WechatMiniProgramExpressBundle\Request\AddOrderRequest;

// 获取服务
$deliveryOrderService = $this->container->get(DeliveryOrderService::class);

// 预下单获取配送费
$preOrderRequest = new PreAddOrderRequest();
$preOrderRequest->setAccount($account);
$preOrderRequest->setDeliveryId('delivery_company_id');
$preOrderRequest->setShopId('shop_id');
// ... 设置其他参数

$preOrderResponse = $deliveryOrderService->preAddOrder($preOrderRequest);

// 正式下单
$orderRequest = new AddOrderRequest();
$orderRequest->setAccount($account);
// ... 设置订单参数

$orderResponse = $deliveryOrderService->addOrder($orderRequest);
```

#### 4. 查询订单

```php
use WechatMiniProgramExpressBundle\Service\OrderQueryService;
use WechatMiniProgramExpressBundle\Request\GetOrderRequest;

$orderQueryService = $this->container->get(OrderQueryService::class);

$queryRequest = new GetOrderRequest();
$queryRequest->setAccount($account);
$queryRequest->setShopOrderId('your_order_id');

$orderInfo = $orderQueryService->getOrder($queryRequest);
```

## 高级用法

### 自定义订单实体

```php
use WechatMiniProgramExpressBundle\Entity\Order;
use WechatMiniProgramExpressBundle\Entity\Embed\SenderInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\ReceiverInfo;

// 创建订单
$order = new Order();

// 设置发送方信息
$senderInfo = new SenderInfo();
$senderInfo->setName('发送方姓名');
$senderInfo->setCity('北京市');
$senderInfo->setAddress('详细地址');
$senderInfo->setPhone('联系电话');
$order->setSenderInfo($senderInfo);

// 设置接收方信息
$receiverInfo = new ReceiverInfo();
$receiverInfo->setName('接收方姓名');
$receiverInfo->setCity('上海市');
$receiverInfo->setAddress('详细地址');
$receiverInfo->setPhone('联系电话');
$order->setReceiverInfo($receiverInfo);

// 转换为请求参数
$requestParams = $order->toRequestArray();
```

### 事件监听

```php
// 监听订单创建事件
class OrderCreatedListener
{
    public function onOrderCreated(OrderCreatedEvent $event): void
    {
        $order = $event->getOrder();
        // 处理订单创建后的业务逻辑
    }
}
```

## 技术架构

Bundle采用分层架构和服务职责分离原则：

```text
graph TD
    A[WechatMiniProgramExpressBundle] --> B[DeliveryConfigService]
    A --> C[DeliveryOrderService]
    A --> D[OrderQueryService]
    A --> E[MockOrderService]

    B --> F[配送公司与绑定账号管理]
    C --> G[订单创建与修改]
    D --> H[订单查询与异常处理]
    E --> I[订单状态模拟更新]

    B -.依赖.-> J[WechatMiniProgramBundle]
    C -.依赖.-> J
    D -.依赖.-> J
    E -.依赖.-> J
```

### 核心服务

1. **DeliveryConfigService**: 配置管理服务
  - 配送公司数据同步与管理
  - 商户绑定账号信息维护

2. **DeliveryOrderService**: 订单操作服务
  - 预下单、正式下单
  - 订单取消、重新下单

3. **OrderQueryService**: 查询服务
  - 订单状态查询
  - 异常处理

4. **MockOrderService**: 测试服务（仅测试环境）
  - 模拟订单状态更新

## 命令行工具

### 同步配送公司列表

```bash
bin/console wechat-express:sync-delivery-companies [--account-id=<account_id>]
```

同步支持的配送公司信息到本地数据库。

**参数:**
- `--account-id`: 可选，指定同步特定账号的配送公司信息

### 同步绑定账号信息

```bash
bin/console wechat-express:sync-bind-accounts [--account-id=<account_id>]
```

同步已绑定的配送账号信息。

**参数:**
- `--account-id`: 可选，指定同步特定账号的绑定信息

## API 参考

本Bundle集成以下微信小程序即时配送API：

- [获取配送公司列表](
  https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/getAllImmeDelivery.html
)
- [拉取已绑定账号](
  https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/getBindAccount.html
)
- [预下配送单](
  https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/preAddOrder.html)
- [下配送单](
  https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/addOrder.html)
- [查询配送单信息](
  https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/getOrder.html)
- [取消配送单](
  https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/cancelOrder.html)
- [重新下单](
  https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/reOrder.html)
- [增加小费](
  https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/addTips.html)
- [异常件退回确认](
  https://developers.weixin.qq.com/miniprogram/dev/OpenApiDoc/immediate-delivery/deliver-by-business/abnormalConfirm.html
)

## 安全注意事项

在使用本 Bundle 时，请注意以下安全事项：

1. **API 密钥保护**: 确保微信小程序的 AppSecret 等敏感信息安全存储，不要硬编码在代码中
2. **数据传输安全**: 所有与微信 API 的通信都通过 HTTPS 进行
3. **订单数据保护**: 订单中可能包含用户隐私信息，请确保符合数据保护法规
4. **访问控制**: 实施适当的权限控制，确保只有授权用户可以操作配送订单

## 依赖关系

- `tourze/wechat-mini-program-bundle`: 微信小程序基础包
- `symfony/framework-bundle`: Symfony 框架核心
- `doctrine/orm`: Doctrine ORM

## 贡献

欢迎贡献代码！请确保：

1. 遵循项目的编码规范
2. 编写相应的测试用例
3. 更新相关文档
4. 提交前运行测试确保通过

## 许可证

本项目采用 MIT 许可证 - 查看 [LICENSE](../../LICENSE) 文件了解详情。
