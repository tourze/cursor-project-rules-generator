<?php

namespace Tourze\CursorPorjectRules\Tests\Model;

use PHPUnit\Framework\TestCase;
use Tourze\CursorPorjectRules\Model\RuleType;

class RuleTypeTest extends TestCase
{
    public function test_enumValues_correct(): void
    {
        $this->assertEquals('always', RuleType::ALWAYS->value);
        $this->assertEquals('auto_attached', RuleType::AUTO_ATTACHED->value);
        $this->assertEquals('agent_requested', RuleType::AGENT_REQUESTED->value);
        $this->assertEquals('manual', RuleType::MANUAL->value);
    }

    public function test_enumCases_complete(): void
    {
        $cases = RuleType::cases();
        
        $this->assertCount(4, $cases);
        
        $values = array_map(fn(RuleType $case) => $case->value, $cases);
        
        $this->assertContains('always', $values);
        $this->assertContains('auto_attached', $values);
        $this->assertContains('agent_requested', $values);
        $this->assertContains('manual', $values);
    }
} 