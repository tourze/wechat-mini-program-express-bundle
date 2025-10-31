<?php

namespace WechatMiniProgramExpressBundle\Tests\Entity\Embed;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Entity\Embed\ReceiverInfo;

/**
 * @internal
 */
#[CoversClass(ReceiverInfo::class)]
final class ReceiverInfoTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $receiverInfo = new ReceiverInfo();

        $receiverInfo->setName('张三');
        $this->assertSame('张三', $receiverInfo->getName());

        $receiverInfo->setPhone('13800138000');
        $this->assertSame('13800138000', $receiverInfo->getPhone());

        $receiverInfo->setCity('北京市');
        $this->assertSame('北京市', $receiverInfo->getCity());

        $receiverInfo->setAddress('朝阳区建国路');
        $this->assertSame('朝阳区建国路', $receiverInfo->getAddress());

        $receiverInfo->setAddressDetail('88号SOHO现代城');
        $this->assertSame('88号SOHO现代城', $receiverInfo->getAddressDetail());

        $receiverInfo->setLng(116.452222);
        $this->assertSame(116.452222, $receiverInfo->getLng());

        $receiverInfo->setLat(39.906217);
        $this->assertSame(39.906217, $receiverInfo->getLat());

        $receiverInfo->setCoordinateType(1);
        $this->assertSame(1, $receiverInfo->getCoordinateType());
    }

    public function testToRequestArray(): void
    {
        $receiverInfo = new ReceiverInfo();
        $receiverInfo->setName('张三');
        $receiverInfo->setPhone('13800138000');
        $receiverInfo->setCity('北京市');
        $receiverInfo->setAddress('朝阳区建国路');
        $receiverInfo->setAddressDetail('88号SOHO现代城');
        $receiverInfo->setLng(116.452222);
        $receiverInfo->setLat(39.906217);
        $receiverInfo->setCoordinateType(1);

        $array = $receiverInfo->toRequestArray();

        $this->assertSame('张三', $array['name']);
        $this->assertSame('13800138000', $array['mobile']);
        $this->assertSame('北京市', $array['city']);
        $this->assertSame('朝阳区建国路', $array['address']);
        $this->assertSame('88号SOHO现代城', $array['address_detail']);
        $this->assertSame(116.452222, $array['lng']);
        $this->assertSame(39.906217, $array['lat']);
        $this->assertSame(1, $array['coordinate_type']);
    }

    public function testToRequestArrayWithoutCoordinates(): void
    {
        $receiverInfo = new ReceiverInfo();
        $receiverInfo->setName('张三');
        $receiverInfo->setPhone('13800138000');
        $receiverInfo->setCity('北京市');
        $receiverInfo->setAddress('朝阳区建国路');

        $array = $receiverInfo->toRequestArray();

        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('mobile', $array);
        $this->assertArrayHasKey('city', $array);
        $this->assertArrayHasKey('address', $array);
        $this->assertArrayNotHasKey('lng', $array);
        $this->assertArrayNotHasKey('lat', $array);
        $this->assertArrayNotHasKey('coordinate_type', $array);
    }

    public function testToRequestArrayWithPartialCoordinates(): void
    {
        $receiverInfo = new ReceiverInfo();
        $receiverInfo->setName('张三');
        $receiverInfo->setLng(116.452222);
        // lat为null

        $array = $receiverInfo->toRequestArray();

        $this->assertArrayHasKey('name', $array);
        $this->assertArrayNotHasKey('lng', $array);
        $this->assertArrayNotHasKey('lat', $array);
        $this->assertArrayNotHasKey('coordinate_type', $array);
    }

    public function testToRequestArrayWithCoordinatesDefaultType(): void
    {
        $receiverInfo = new ReceiverInfo();
        $receiverInfo->setName('张三');
        $receiverInfo->setLng(116.452222);
        $receiverInfo->setLat(39.906217);
        // coordinateType为null，应该默认为0

        $array = $receiverInfo->toRequestArray();

        $this->assertSame(116.452222, $array['lng']);
        $this->assertSame(39.906217, $array['lat']);
        $this->assertSame(0, $array['coordinate_type']);
    }

    public function testFromArray(): void
    {
        $data = [
            'name' => '张三',
            'phone' => '13800138000',
            'city' => '北京市',
            'address' => '朝阳区建国路',
            'address_detail' => '88号SOHO现代城',
            'lng' => 116.452222,
            'lat' => 39.906217,
            'coordinate_type' => 1,
        ];

        $receiverInfo = ReceiverInfo::fromArray($data);

        $this->assertSame('张三', $receiverInfo->getName());
        $this->assertSame('13800138000', $receiverInfo->getPhone());
        $this->assertSame('北京市', $receiverInfo->getCity());
        $this->assertSame('朝阳区建国路', $receiverInfo->getAddress());
        $this->assertSame('88号SOHO现代城', $receiverInfo->getAddressDetail());
        $this->assertSame(116.452222, $receiverInfo->getLng());
        $this->assertSame(39.906217, $receiverInfo->getLat());
        $this->assertSame(1, $receiverInfo->getCoordinateType());
    }

    public function testFromArrayWithPartialData(): void
    {
        $data = [
            'name' => '张三',
            'phone' => '13800138000',
        ];

        $receiverInfo = ReceiverInfo::fromArray($data);

        $this->assertSame('张三', $receiverInfo->getName());
        $this->assertSame('13800138000', $receiverInfo->getPhone());
        $this->assertNull($receiverInfo->getCity());
        $this->assertNull($receiverInfo->getAddress());
        $this->assertNull($receiverInfo->getLng());
        $this->assertNull($receiverInfo->getLat());
    }

    public function testFromArrayWithEmptyData(): void
    {
        $receiverInfo = ReceiverInfo::fromArray([]);

        $this->assertNull($receiverInfo->getName());
        $this->assertNull($receiverInfo->getPhone());
        $this->assertNull($receiverInfo->getCity());
        $this->assertNull($receiverInfo->getAddress());
    }
}
