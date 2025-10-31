<?php

namespace Tourze\CursorProjectRules\Tests\Model\Rule;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\CursorProjectRules\Model\Rule\BaseRule;
use Tourze\CursorProjectRules\Model\RuleType;
use Tourze\CursorProjectRules\ValueObject\ProjectRule;

/**
 * @internal
 */
#[CoversClass(BaseRule::class)]
final class BaseRuleTest extends TestCase
{
    public function testToProjectRuleConversion(): void
    {
        $rule = new ConcreteRule(
            'test-rule',
            'Test description',
            RuleType::AGENT_REQUESTED,
            ['*.php'],
            'Test content',
            ['ref.md']
        );

        $projectRule = $rule->toProjectRule();

        $this->assertInstanceOf(ProjectRule::class, $projectRule);
        $this->assertEquals('test-rule', $projectRule->name);
        $this->assertEquals('Test description', $projectRule->description);
        $this->assertEquals(RuleType::AGENT_REQUESTED, $projectRule->type);
        $this->assertEquals(['*.php'], $projectRule->globs);
        $this->assertFalse($projectRule->alwaysApply);
        $this->assertEquals('Test content', $projectRule->content);
        $this->assertEquals(['ref.md'], $projectRule->referencedFiles);
    }

    public function testIsAlwaysApplyAlwaysType(): void
    {
        $rule = new ConcreteRule(
            'always-rule',
            'Always rule',
            RuleType::ALWAYS,
            [],
            'Content'
        );

        $this->assertTrue($rule->isAlwaysApply());
    }

    public function testIsAlwaysApplyOtherTypes(): void
    {
        $types = [RuleType::MANUAL, RuleType::AGENT_REQUESTED, RuleType::AUTO_ATTACHED];

        foreach ($types as $type) {
            $rule = new ConcreteRule(
                'test-rule',
                'Test description',
                $type,
                [],
                'Content'
            );

            $this->assertFalse($rule->isAlwaysApply());
        }
    }
}
