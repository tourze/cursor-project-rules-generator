<?php

namespace Tourze\CursorPorjectRules\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use Tourze\CursorPorjectRules\Exception\RuleFileNotFoundException;

final class RuleFileNotFoundExceptionTest extends TestCase
{
    public function testFileNotFound(): void
    {
        $exception = RuleFileNotFoundException::fileNotFound('/path/to/file.mdc');
        
        $this->assertInstanceOf(RuleFileNotFoundException::class, $exception);
        $this->assertInstanceOf(\InvalidArgumentException::class, $exception);
        $this->assertSame('规则文件不存在: /path/to/file.mdc', $exception->getMessage());
    }
}