<?php

namespace Tourze\CursorPorjectRules\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use Tourze\CursorPorjectRules\Exception\InvalidRuleTypeException;

final class InvalidRuleTypeExceptionTest extends TestCase
{
    public function testUnknownType(): void
    {
        $exception = InvalidRuleTypeException::unknownType('invalid');
        
        $this->assertInstanceOf(InvalidRuleTypeException::class, $exception);
        $this->assertInstanceOf(\InvalidArgumentException::class, $exception);
        $this->assertSame('未知的规则类型: invalid', $exception->getMessage());
    }
}