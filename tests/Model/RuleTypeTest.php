<?php

namespace Tourze\CursorProjectRules\Tests\Model;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\CursorProjectRules\Model\RuleType;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;

/**
 * @internal
 */
#[CoversClass(RuleType::class)]
final class RuleTypeTest extends AbstractEnumTestCase
{
    public function testToArray(): void
    {
        $ruleType = RuleType::ALWAYS;
        $array = $ruleType->toArray();

        $this->assertArrayHasKey('value', $array);
        $this->assertArrayHasKey('label', $array);
        $this->assertEquals('always', $array['value']);
        $this->assertEquals('始终包含', $array['label']);
        $this->assertCount(2, $array, '数组应该只包含value和label两个键');
    }

    public function testGenOptions(): void
    {
        $options = RuleType::genOptions();

        $this->assertCount(4, $options);

        foreach ($options as $option) {
            $this->assertArrayHasKey('value', $option);
            $this->assertArrayHasKey('label', $option);
            $this->assertIsString($option['value']);
            $this->assertIsString($option['label']);
        }

        $this->assertEquals('always', $options[0]['value']);
        $this->assertEquals('始终包含', $options[0]['label']);
    }
}
