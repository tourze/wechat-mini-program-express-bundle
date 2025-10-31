<?php

namespace WechatMiniProgramExpressBundle\Tests\Request;

use HttpClientBundle\Tests\Request\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Request\GetAllImmeDeliveryRequest;

/**
 * @internal
 */
#[CoversClass(GetAllImmeDeliveryRequest::class)]
final class GetAllImmeDeliveryRequestTest extends RequestTestCase
{
    private GetAllImmeDeliveryRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        // 使用容器获取服务实例，这符合集成测试最佳实践
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
