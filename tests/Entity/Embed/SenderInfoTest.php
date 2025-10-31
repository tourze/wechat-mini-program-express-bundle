<?php

namespace WechatMiniProgramExpressBundle\Tests\Entity\Embed;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Entity\Embed\SenderInfo;

/**
 * @internal
 */
#[CoversClass(SenderInfo::class)]
final class SenderInfoTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $senderInfo = new SenderInfo();

        $senderInfo->setName('李四');
        $this->assertSame('李四', $senderInfo->getName());

        $senderInfo->setPhone('13800138001');
        $this->assertSame('13800138001', $senderInfo->getPhone());

        $senderInfo->setCity('上海市');
        $this->assertSame('上海市', $senderInfo->getCity());

        $senderInfo->setAddress('浦东新区陆家嘴');
        $this->assertSame('浦东新区陆家嘴', $senderInfo->getAddress());

        $senderInfo->setAddressDetail('世纪大道100号');
        $this->assertSame('世纪大道100号', $senderInfo->getAddressDetail());

        $senderInfo->setLng(121.499763);
        $this->assertSame(121.499763, $senderInfo->getLng());

        $senderInfo->setLat(31.239666);
        $this->assertSame(31.239666, $senderInfo->getLat());

        $senderInfo->setCoordinateType(1);
        $this->assertSame(1, $senderInfo->getCoordinateType());
    }

    public function testToRequestArray(): void
    {
        $senderInfo = new SenderInfo();
        $senderInfo->setName('李四');
        $senderInfo->setPhone('13800138001');
        $senderInfo->setCity('上海市');
        $senderInfo->setAddress('浦东新区陆家嘴');
        $senderInfo->setAddressDetail('世纪大道100号');
        $senderInfo->setLng(121.499763);
        $senderInfo->setLat(31.239666);
        $senderInfo->setCoordinateType(1);

        $array = $senderInfo->toRequestArray();

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
        $senderInfo = new SenderInfo();
        $senderInfo->setName('李四');
        $senderInfo->setPhone('13800138001');
        $senderInfo->setCity('上海市');
        $senderInfo->setAddress('浦东新区陆家嘴');

        $array = $senderInfo->toRequestArray();

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
        $senderInfo = new SenderInfo();
        $senderInfo->setName('李四');
        $senderInfo->setLng(121.499763);
        // lat为null

        $array = $senderInfo->toRequestArray();

        $this->assertArrayHasKey('name', $array);
        $this->assertArrayNotHasKey('lng', $array);
        $this->assertArrayNotHasKey('lat', $array);
        $this->assertArrayNotHasKey('coordinate_type', $array);
    }

    public function testToRequestArrayWithCoordinatesDefaultType(): void
    {
        $senderInfo = new SenderInfo();
        $senderInfo->setName('李四');
        $senderInfo->setLng(121.499763);
        $senderInfo->setLat(31.239666);
        // coordinateType为null，应该默认为0

        $array = $senderInfo->toRequestArray();

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
