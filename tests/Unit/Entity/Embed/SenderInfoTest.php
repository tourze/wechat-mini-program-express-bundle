<?php

namespace WechatMiniProgramExpressBundle\Tests\Unit\Entity\Embed;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Entity\Embed\SenderInfo;

class SenderInfoTest extends TestCase
{
    private SenderInfo $senderInfo;

    protected function setUp(): void
    {
        $this->senderInfo = new SenderInfo();
    }

    public function testGettersAndSetters(): void
    {
        $name = '李四';
        $phone = '13800138001';
        $city = '上海市';
        $address = '浦东新区陆家嘴';
        $addressDetail = '世纪大道100号';
        $lng = 121.499763;
        $lat = 31.239666;
        $coordinateType = 1;

        $this->senderInfo->setName($name);
        $this->senderInfo->setPhone($phone);
        $this->senderInfo->setCity($city);
        $this->senderInfo->setAddress($address);
        $this->senderInfo->setAddressDetail($addressDetail);
        $this->senderInfo->setLng($lng);
        $this->senderInfo->setLat($lat);
        $this->senderInfo->setCoordinateType($coordinateType);

        $this->assertSame($name, $this->senderInfo->getName());
        $this->assertSame($phone, $this->senderInfo->getPhone());
        $this->assertSame($city, $this->senderInfo->getCity());
        $this->assertSame($address, $this->senderInfo->getAddress());
        $this->assertSame($addressDetail, $this->senderInfo->getAddressDetail());
        $this->assertSame($lng, $this->senderInfo->getLng());
        $this->assertSame($lat, $this->senderInfo->getLat());
        $this->assertSame($coordinateType, $this->senderInfo->getCoordinateType());
    }

    public function testToRequestArray(): void
    {
        $this->senderInfo->setName('李四');
        $this->senderInfo->setPhone('13800138001');
        $this->senderInfo->setCity('上海市');
        $this->senderInfo->setAddress('浦东新区陆家嘴');
        $this->senderInfo->setAddressDetail('世纪大道100号');
        $this->senderInfo->setLng(121.499763);
        $this->senderInfo->setLat(31.239666);
        $this->senderInfo->setCoordinateType(1);

        $array = $this->senderInfo->toRequestArray();

        $this->assertSame('李四', $array['name']);
        $this->assertSame('13800138001', $array['mobile']);
        $this->assertSame('上海市', $array['city']);
        $this->assertSame('浦东新区陆家嘴', $array['address']);
        $this->assertSame('世纪大道100号', $array['address_detail']);
        $this->assertSame(121.499763, $array['lng']);
        $this->assertSame(31.239666, $array['lat']);
        $this->assertSame(1, $array['coordinate_type']);
    }

    public function testToRequestArrayWithoutCoordinates(): void
    {
        $this->senderInfo->setName('李四');
        $this->senderInfo->setPhone('13800138001');
        $this->senderInfo->setCity('上海市');
        $this->senderInfo->setAddress('浦东新区陆家嘴');

        $array = $this->senderInfo->toRequestArray();

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
        $this->senderInfo->setName('李四');
        $this->senderInfo->setLng(121.499763);
        // lat为null

        $array = $this->senderInfo->toRequestArray();

        $this->assertArrayHasKey('name', $array);
        $this->assertArrayNotHasKey('lng', $array);
        $this->assertArrayNotHasKey('lat', $array);
        $this->assertArrayNotHasKey('coordinate_type', $array);
    }

    public function testToRequestArrayWithCoordinatesDefaultType(): void
    {
        $this->senderInfo->setName('李四');
        $this->senderInfo->setLng(121.499763);
        $this->senderInfo->setLat(31.239666);
        // coordinateType为null，应该默认为0

        $array = $this->senderInfo->toRequestArray();

        $this->assertSame(121.499763, $array['lng']);
        $this->assertSame(31.239666, $array['lat']);
        $this->assertSame(0, $array['coordinate_type']);
    }

    public function testFromArray(): void
    {
        $data = [
            'name' => '李四',
            'phone' => '13800138001',
            'city' => '上海市',
            'address' => '浦东新区陆家嘴',
            'address_detail' => '世纪大道100号',
            'lng' => 121.499763,
            'lat' => 31.239666,
            'coordinate_type' => 1,
        ];

        $senderInfo = SenderInfo::fromArray($data);

        $this->assertSame('李四', $senderInfo->getName());
        $this->assertSame('13800138001', $senderInfo->getPhone());
        $this->assertSame('上海市', $senderInfo->getCity());
        $this->assertSame('浦东新区陆家嘴', $senderInfo->getAddress());
        $this->assertSame('世纪大道100号', $senderInfo->getAddressDetail());
        $this->assertSame(121.499763, $senderInfo->getLng());
        $this->assertSame(31.239666, $senderInfo->getLat());
        $this->assertSame(1, $senderInfo->getCoordinateType());
    }

    public function testFromArrayWithPartialData(): void
    {
        $data = [
            'name' => '李四',
            'phone' => '13800138001',
        ];

        $senderInfo = SenderInfo::fromArray($data);

        $this->assertSame('李四', $senderInfo->getName());
        $this->assertSame('13800138001', $senderInfo->getPhone());
        $this->assertNull($senderInfo->getCity());
        $this->assertNull($senderInfo->getAddress());
        $this->assertNull($senderInfo->getLng());
        $this->assertNull($senderInfo->getLat());
    }

    public function testFromArrayWithEmptyData(): void
    {
        $senderInfo = SenderInfo::fromArray([]);

        $this->assertNull($senderInfo->getName());
        $this->assertNull($senderInfo->getPhone());
        $this->assertNull($senderInfo->getCity());
        $this->assertNull($senderInfo->getAddress());
    }
}