<?php

namespace Tourze\CursorProjectRules\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\CursorProjectRules\Exception\InvalidMDCFormatException;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;

/**
 * @internal
 */
#[CoversClass(InvalidMDCFormatException::class)]
final class InvalidMDCFormatExceptionTest extends AbstractExceptionTestCase
{
    public function testInvalidFormat(): void
    {
        $exception = InvalidMDCFormatException::invalidFormat();

        $this->assertInstanceOf(InvalidMDCFormatException::class, $exception);
        $this->assertInstanceOf(\InvalidArgumentException::class, $exception);
        $this->assertSame('无效的MDC内容格式', $exception->getMessage());
    }
}
