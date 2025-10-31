<?php

namespace Tourze\CursorProjectRules\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;

// 临时解决自动加载问题 - 手动包含依赖
require_once __DIR__ . '/../../src/Exception/InvalidRuleTypeException.php';

use Tourze\CursorProjectRules\Exception\InvalidRuleTypeException;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;

/**
 * @internal
 */
#[CoversClass(InvalidRuleTypeException::class)]
final class InvalidRuleTypeExceptionTest extends AbstractExceptionTestCase
{
    public function testUnknownType(): void
    {
        $exception = InvalidRuleTypeException::unknownType('invalid');

        $this->assertInstanceOf(InvalidRuleTypeException::class, $exception);
        $this->assertInstanceOf(\InvalidArgumentException::class, $exception);
        $this->assertSame('未知的规则类型: invalid', $exception->getMessage());
    }
}
