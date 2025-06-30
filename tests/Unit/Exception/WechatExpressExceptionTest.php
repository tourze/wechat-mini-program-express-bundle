<?php

namespace WechatMiniProgramExpressBundle\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Exception\WechatExpressException;

class WechatExpressExceptionTest extends TestCase
{
    public function testExceptionCanBeInstantiated(): void
    {
        $exception = new WechatExpressException();
        $this->assertInstanceOf(WechatExpressException::class, $exception);
        $this->assertInstanceOf(\Exception::class, $exception);
    }

    public function testExceptionWithMessage(): void
    {
        $message = '微信接口调用失败';
        $exception = new WechatExpressException($message);
        
        $this->assertSame($message, $exception->getMessage());
    }

    public function testExceptionWithMessageAndCode(): void
    {
        $message = '微信接口调用失败';
        $code = 40001;
        $exception = new WechatExpressException($message, $code);
        
        $this->assertSame($message, $exception->getMessage());
        $this->assertSame($code, $exception->getCode());
    }

    public function testExceptionWithPreviousException(): void
    {
        $previousException = new \RuntimeException('网络错误');
        $exception = new WechatExpressException('微信接口调用失败', 0, $previousException);
        
        $this->assertSame($previousException, $exception->getPrevious());
    }

    public function testExceptionInheritance(): void
    {
        $exception = new WechatExpressException();
        $this->assertInstanceOf(\Exception::class, $exception);
    }
}