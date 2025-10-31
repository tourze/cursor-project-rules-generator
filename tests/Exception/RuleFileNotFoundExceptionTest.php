<?php

namespace Tourze\CursorProjectRules\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;

// 临时解决自动加载问题 - 手动包含依赖
require_once __DIR__ . '/../../src/Exception/RuleFileNotFoundException.php';

use Tourze\CursorProjectRules\Exception\RuleFileNotFoundException;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;

/**
 * @internal
 */
#[CoversClass(RuleFileNotFoundException::class)]
final class RuleFileNotFoundExceptionTest extends AbstractExceptionTestCase
{
    public function testFileNotFound(): void
    {
        $exception = RuleFileNotFoundException::fileNotFound('/path/to/file.mdc');

        $this->assertInstanceOf(RuleFileNotFoundException::class, $exception);
        $this->assertInstanceOf(\InvalidArgumentException::class, $exception);
        $this->assertSame('规则文件不存在: /path/to/file.mdc', $exception->getMessage());
    }
}
