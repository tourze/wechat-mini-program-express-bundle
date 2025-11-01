<?php

namespace WechatMiniProgramExpressBundle\Tests\Request;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatMiniProgramExpressBundle\Request\GetBindAccountRequest;

/**
 * @internal
 */
#[CoversClass(GetBindAccountRequest::class)]
final class GetBindAccountRequestTest extends RequestTestCase
{
    private GetBindAccountRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        // 使用容器获取服务实例，这符合集成测试最佳实践
        $this->request = new GetBindAccountRequest();
    }

    public function testRequestCanBeInstantiated(): void
    {
        $this->assertInstanceOf(GetBindAccountRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $this->assertSame('/cgi-bin/express/local/business/shop/get', $this->request->getRequestPath());
    }

    public function testGetRequestOptions(): void
    {
        $this->assertSame([], $this->request->getRequestOptions());
    }

    public function testGetRequestMethod(): void
    {
        $this->assertSame('GET', $this->request->getRequestMethod());
    }
}
