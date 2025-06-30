<?php

namespace WechatMiniProgramExpressBundle\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use WechatMiniProgramExpressBundle\Exception\DeliveryException;

class DeliveryExceptionTest extends TestCase
{
    public function testExceptionCanBeInstantiated(): void
    {
        $exception = new DeliveryException();
        $this->assertInstanceOf(DeliveryException::class, $exception);
        $this->assertInstanceOf(\Exception::class, $exception);
    }

    public function testExceptionWithMessage(): void
    {
        $message = '配送失败';
        $exception = new DeliveryException($message);
        
        $this->assertSame($message, $exception->getMessage());
    }

    public function testExceptionWithMessageAndCode(): void
    {
        $message = '配送失败';
        $code = 500;
        $exception = new DeliveryException($message, $code);
        
        $this->assertSame($message, $exception->getMessage());
        $this->assertSame($code, $exception->getCode());
    }

    public function testExceptionWithPreviousException(): void
    {
        $previousException = new \RuntimeException('原始错误');
        $exception = new DeliveryException('配送失败', 0, $previousException);
        
        $this->assertSame($previousException, $exception->getPrevious());
    }

    public function testExceptionInheritance(): void
    {
        $exception = new DeliveryException();
        $this->assertInstanceOf(\Exception::class, $exception);
    }
}