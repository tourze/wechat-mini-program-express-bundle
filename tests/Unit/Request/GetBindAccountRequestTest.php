<?php

namespace WechatMiniProgramExpressBundle\Tests\Unit\Request;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Request\GetBindAccountRequest;

class GetBindAccountRequestTest extends TestCase
{
    private GetBindAccountRequest $request;

    protected function setUp(): void
    {
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