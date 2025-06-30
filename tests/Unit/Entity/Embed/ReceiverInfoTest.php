<?php

namespace WechatMiniProgramExpressBundle\Tests\Unit\Entity\Embed;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Entity\Embed\ReceiverInfo;

class ReceiverInfoTest extends TestCase
{
    private ReceiverInfo $receiverInfo;

    protected function setUp(): void
    {
        $this->receiverInfo = new ReceiverInfo();
    }

    public function testGettersAndSetters(): void
    {
        $name = '张三';
        $phone = '13800138000';
        $city = '北京市';
        $address = '朝阳区建国路';
        $addressDetail = '88号SOHO现代城';
        $lng = 116.452222;
        $lat = 39.906217;
        $coordinateType = 1;

        $this->receiverInfo->setName($name);
        $this->receiverInfo->setPhone($phone);
        $this->receiverInfo->setCity($city);
        $this->receiverInfo->setAddress($address);
        $this->receiverInfo->setAddressDetail($addressDetail);
        $this->receiverInfo->setLng($lng);
        $this->receiverInfo->setLat($lat);
        $this->receiverInfo->setCoordinateType($coordinateType);

        $this->assertSame($name, $this->receiverInfo->getName());
        $this->assertSame($phone, $this->receiverInfo->getPhone());
        $this->assertSame($city, $this->receiverInfo->getCity());
        $this->assertSame($address, $this->receiverInfo->getAddress());
        $this->assertSame($addressDetail, $this->receiverInfo->getAddressDetail());
        $this->assertSame($lng, $this->receiverInfo->getLng());
        $this->assertSame($lat, $this->receiverInfo->getLat());
        $this->assertSame($coordinateType, $this->receiverInfo->getCoordinateType());
    }

    public function testToRequestArray(): void
    {
        $this->receiverInfo->setName('张三');
        $this->receiverInfo->setPhone('13800138000');
        $this->receiverInfo->setCity('北京市');
        $this->receiverInfo->setAddress('朝阳区建国路');
        $this->receiverInfo->setAddressDetail('88号SOHO现代城');
        $this->receiverInfo->setLng(116.452222);
        $this->receiverInfo->setLat(39.906217);
        $this->receiverInfo->setCoordinateType(1);

        $array = $this->receiverInfo->toRequestArray();

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
        $this->receiverInfo->setName('张三');
        $this->receiverInfo->setPhone('13800138000');
        $this->receiverInfo->setCity('北京市');
        $this->receiverInfo->setAddress('朝阳区建国路');

        $array = $this->receiverInfo->toRequestArray();

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
        $this->receiverInfo->setName('张三');
        $this->receiverInfo->setLng(116.452222);
        // lat为null

        $array = $this->receiverInfo->toRequestArray();

        $this->assertArrayHasKey('name', $array);
        $this->assertArrayNotHasKey('lng', $array);
        $this->assertArrayNotHasKey('lat', $array);
        $this->assertArrayNotHasKey('coordinate_type', $array);
    }

    public function testToRequestArrayWithCoordinatesDefaultType(): void
    {
        $this->receiverInfo->setName('张三');
        $this->receiverInfo->setLng(116.452222);
        $this->receiverInfo->setLat(39.906217);
        // coordinateType为null，应该默认为0

        $array = $this->receiverInfo->toRequestArray();

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