<?php

namespace WechatMiniProgramExpressBundle\Tests\Request;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatMiniProgramBundle\Entity\Account;
use WechatMiniProgramExpressBundle\Entity\Embed\CargoInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\OrderInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\ReceiverInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\SenderInfo;
use WechatMiniProgramExpressBundle\Entity\Embed\ShopInfo;
use WechatMiniProgramExpressBundle\Request\AddOrderRequest;

/**
 * @internal
 */
#[CoversClass(AddOrderRequest::class)]
final class AddOrderRequestTest extends RequestTestCase
{
    private AddOrderRequest $request;

    private Account $account;

    protected function setUp(): void
    {
        parent::setUp();

        // 使用容器获取服务实例，这符合集成测试最佳实践
        $this->request = new AddOrderRequest();
        // 使用匿名类代替Mock，遵循静态分析规范
        // Account 是 Doctrine 实体类，包含特定的用户身份信息
        // 在请求类测试中使用匿名类模拟账户实体，用于测试请求构建逻辑
        $this->account = new class extends Account {
            public function getId(): int
            {
                return 1;
            }

            public function getAppId(): string
            {
                return 'test-app-id';
            }

            public function getAppSecret(): string
            {
                return 'test-app-secret';
            }

            public function getName(): string
            {
                return 'Test Account';
            }
        };
        $this->request->setAccount($this->account);
    }

    public function testSettersAndGetters(): void
    {
        $shopId = 'shop-123';
        $shopNo = 'no-456';
        $deliveryId = 'delivery-789';
        $shopOrderId = 'order-abc';

        $this->request->setShopId($shopId);
        $this->request->setShopNo($shopNo);
        $this->request->setDeliveryId($deliveryId);
        $this->request->setShopOrderId($shopOrderId);

        $this->assertEquals($shopId, $this->request->getShopId());
        $this->assertEquals($shopNo, $this->request->getShopNo());
        $this->assertEquals($deliveryId, $this->request->getDeliveryId());
        $this->assertEquals($shopOrderId, $this->request->getShopOrderId());
    }

    public function testSetSender(): void
    {
        $sender = new SenderInfo();
        $sender->setName('发送方姓名');
        $sender->setPhone('13800138000');
        $sender->setAddress('发送方地址');
        $sender->setLat(39.9);
        $sender->setLng(116.4);

        $this->request->setSender($sender);

        $requestArray = $this->request->toArray();
        $this->assertArrayHasKey('sender', $requestArray);
        $this->assertIsArray($requestArray['sender']);
        $this->assertEquals('发送方姓名', $requestArray['sender']['name']);
        $this->assertEquals('13800138000', $requestArray['sender']['mobile']);
        $this->assertEquals('发送方地址', $requestArray['sender']['address']);
        $this->assertEquals(39.9, $requestArray['sender']['lat']);
        $this->assertEquals(116.4, $requestArray['sender']['lng']);
    }

    public function testSetReceiver(): void
    {
        $receiver = new ReceiverInfo();
        $receiver->setName('接收方姓名');
        $receiver->setPhone('13800138001');
        $receiver->setAddress('接收方地址');
        $receiver->setLat(40.0);
        $receiver->setLng(116.5);

        $this->request->setReceiver($receiver);

        $requestArray = $this->request->toArray();
        $this->assertArrayHasKey('receiver', $requestArray);
        $this->assertIsArray($requestArray['receiver']);
        $this->assertEquals('接收方姓名', $requestArray['receiver']['name']);
        $this->assertEquals('13800138001', $requestArray['receiver']['mobile']);
        $this->assertEquals('接收方地址', $requestArray['receiver']['address']);
        $this->assertEquals(40.0, $requestArray['receiver']['lat']);
        $this->assertEquals(116.5, $requestArray['receiver']['lng']);
    }

    public function testSetCargo(): void
    {
        $cargo = new CargoInfo();
        $cargo->setGoodsValue(100);
        $cargo->setGoodsHeight(10);
        $cargo->setGoodsLength(20);
        $cargo->setGoodsWidth(15);
        $cargo->setGoodsWeight(2);
        $cargo->setGoodsDetail('商品详情');
        $cargo->setGoodsCount(2);
        $cargo->setCargoFirstClass('食品');
        $cargo->setCargoSecondClass('快餐');

        $this->request->setCargo($cargo);

        $requestArray = $this->request->toArray();
        $this->assertArrayHasKey('cargo', $requestArray);
        $this->assertIsArray($requestArray['cargo']);
        $this->assertEquals(100, $requestArray['cargo']['goods_value']);
        $this->assertEquals(10, $requestArray['cargo']['goods_height']);
        $this->assertEquals(20, $requestArray['cargo']['goods_length']);
        $this->assertEquals(15, $requestArray['cargo']['goods_width']);
        $this->assertEquals(2, $requestArray['cargo']['goods_weight']);
        $this->assertEquals('商品详情', $requestArray['cargo']['goods_detail']);
        $this->assertEquals(2, $requestArray['cargo']['goods_count']);
        $this->assertEquals('食品', $requestArray['cargo']['cargo_first_class']);
        $this->assertEquals('快餐', $requestArray['cargo']['cargo_second_class']);
    }

    public function testSetOrderInfo(): void
    {
        $orderInfo = new OrderInfo();
        $orderInfo->setDeliveryServiceCode('xxx');
        $orderInfo->setOrderType(0);
        $orderInfo->setExpectedDeliveryTime(time() + 3600);
        $orderInfo->setPoiSeq('shop-order-123');
        $orderInfo->setNote('备注');

        $this->request->setOrderInfo($orderInfo);

        $requestArray = $this->request->toArray();
        $this->assertArrayHasKey('order_info', $requestArray);
        $this->assertIsArray($requestArray['order_info']);
        $this->assertEquals('xxx', $requestArray['order_info']['delivery_service_code']);
        $this->assertEquals(0, $requestArray['order_info']['order_type']);
        $this->assertEquals('shop-order-123', $requestArray['order_info']['poi_seq']);
        $this->assertEquals('备注', $requestArray['order_info']['note']);
        $this->assertArrayHasKey('expected_delivery_time', $requestArray['order_info']);
    }

    public function testSetShop(): void
    {
        $shop = new ShopInfo();
        $shop->setWechatAppId('wx123456');
        $shop->setImgUrl('http://example.com/img.jpg');
        $shop->setGoodsName('测试商品');
        $shop->setDeliverySign('sign123');

        $this->request->setShop($shop);

        $requestArray = $this->request->toArray();
        $this->assertArrayHasKey('shop', $requestArray);
        $this->assertIsArray($requestArray['shop']);
        $this->assertEquals('wx123456', $requestArray['shop']['wxa_path']);
        $this->assertEquals('http://example.com/img.jpg', $requestArray['shop']['img_url']);
        $this->assertEquals('测试商品', $requestArray['shop']['goods_name']);
        $this->assertEquals('sign123', $requestArray['shop']['delivery_sign']);
    }

    public function testToArray(): void
    {
        $shopId = 'shop-123';
        $shopNo = 'no-456';
        $deliveryId = 'delivery-789';
        $shopOrderId = 'order-abc';

        $this->request->setShopId($shopId);
        $this->request->setShopNo($shopNo);
        $this->request->setDeliveryId($deliveryId);
        $this->request->setShopOrderId($shopOrderId);

        $requestArray = $this->request->toArray();
        $this->assertIsArray($requestArray);
        $this->assertArrayHasKey('shopid', $requestArray);
        $this->assertArrayHasKey('shop_no', $requestArray);
        $this->assertArrayHasKey('delivery_id', $requestArray);
        $this->assertArrayHasKey('shop_order_id', $requestArray);

        $this->assertEquals($shopId, $requestArray['shopid']);
        $this->assertEquals($shopNo, $requestArray['shop_no']);
        $this->assertEquals($deliveryId, $requestArray['delivery_id']);
        $this->assertEquals($shopOrderId, $requestArray['shop_order_id']);
    }

    public function testGetAppApiType(): void
    {
        $apiType = $this->request->getAppApiType();
        $this->assertEquals('miniprogram', $apiType);
    }

    public function testIsRequireAccessToken(): void
    {
        $requireAccessToken = $this->request->isRequireAccessToken();
        $this->assertTrue($requireAccessToken);
    }
}
