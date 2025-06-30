<?php

namespace Tourze\CursorPorjectRules\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use Tourze\CursorPorjectRules\Exception\InvalidMDCFormatException;

final class InvalidMDCFormatExceptionTest extends TestCase
{
    public function testInvalidFormat(): void
    {
        $exception = InvalidMDCFormatException::invalidFormat();
        
        $this->assertInstanceOf(InvalidMDCFormatException::class, $exception);
        $this->assertInstanceOf(\InvalidArgumentException::class, $exception);
        $this->assertSame('无效的MDC内容格式', $exception->getMessage());
    }
}