<?php

namespace WechatMiniProgramExpressBundle\Tests\Unit\Request;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Request\GetAllImmeDeliveryRequest;

class GetAllImmeDeliveryRequestTest extends TestCase
{
    private GetAllImmeDeliveryRequest $request;

    protected function setUp(): void
    {
        $this->request = new GetAllImmeDeliveryRequest();
    }

    public function testRequestCanBeInstantiated(): void
    {
        $this->assertInstanceOf(GetAllImmeDeliveryRequest::class, $this->request);
    }

    public function testGetRequestPath(): void
    {
        $this->assertSame('/cgi-bin/express/local/business/delivery/getall', $this->request->getRequestPath());
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