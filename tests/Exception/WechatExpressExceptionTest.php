<?php

namespace WechatMiniProgramExpressBundle\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;
use WechatMiniProgramExpressBundle\Exception\WechatExpressException;

/**
 * @internal
 */
#[CoversClass(WechatExpressException::class)]
final class WechatExpressExceptionTest extends AbstractExceptionTestCase
{
    public function testExceptionCanBeInstantiated(): void
    {
        // 异常类可以直接实例化，不需要通过容器获取
        $exception = new WechatExpressException();
        $this->assertInstanceOf(WechatExpressException::class, $exception);
        $this->assertInstanceOf(\Exception::class, $exception);
    }

    public function testExceptionWithMessage(): void
    {
        $message = '微信接口调用失败';
        // 异常类可以直接实例化，不需要通过容器获取
        $exception = new WechatExpressException($message);

        $this->assertSame($message, $exception->getMessage());
    }

    public function testExceptionWithMessageAndCode(): void
    {
        $message = '微信接口调用失败';
        $code = 40001;
        // 异常类可以直接实例化，不需要通过容器获取
        $exception = new WechatExpressException($message, $code);

        $this->assertSame($message, $exception->getMessage());
        $this->assertSame($code, $exception->getCode());
    }

    public function testExceptionWithPreviousException(): void
    {
        $previousException = new \RuntimeException('网络错误');
        // 异常类可以直接实例化，不需要通过容器获取
        $exception = new WechatExpressException('微信接口调用失败', 0, $previousException);

        $this->assertSame($previousException, $exception->getPrevious());
    }

    public function testExceptionInheritance(): void
    {
        // 异常类可以直接实例化，不需要通过容器获取
        $exception = new WechatExpressException();
        $this->assertInstanceOf(\Exception::class, $exception);
    }
}
