<?php

namespace WechatMiniProgramExpressBundle\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;
use WechatMiniProgramExpressBundle\Exception\DeliveryException;

/**
 * @internal
 */
#[CoversClass(DeliveryException::class)]
final class DeliveryExceptionTest extends AbstractExceptionTestCase
{
    public function testExceptionCanBeInstantiated(): void
    {
        // 异常类可以直接实例化，不需要通过容器获取
        $exception = new DeliveryException();
        $this->assertInstanceOf(DeliveryException::class, $exception);
        $this->assertInstanceOf(\Exception::class, $exception);
    }

    public function testExceptionWithMessage(): void
    {
        $message = '配送失败';
        // 异常类可以直接实例化，不需要通过容器获取
        $exception = new DeliveryException($message);

        $this->assertSame($message, $exception->getMessage());
    }

    public function testExceptionWithMessageAndCode(): void
    {
        $message = '配送失败';
        $code = 500;
        // 异常类可以直接实例化，不需要通过容器获取
        $exception = new DeliveryException($message, $code);

        $this->assertSame($message, $exception->getMessage());
        $this->assertSame($code, $exception->getCode());
    }

    public function testExceptionWithPreviousException(): void
    {
        $previousException = new \RuntimeException('原始错误');
        // 异常类可以直接实例化，不需要通过容器获取
        $exception = new DeliveryException('配送失败', 0, $previousException);

        $this->assertSame($previousException, $exception->getPrevious());
    }

    public function testExceptionInheritance(): void
    {
        // 异常类可以直接实例化，不需要通过容器获取
        $exception = new DeliveryException();
        $this->assertInstanceOf(\Exception::class, $exception);
    }
}
