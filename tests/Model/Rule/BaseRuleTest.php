<?php

namespace Tourze\CursorPorjectRules\Tests\Model\Rule;

use PHPUnit\Framework\TestCase;
use Tourze\CursorPorjectRules\Model\Rule\BaseRule;
use Tourze\CursorPorjectRules\Model\RuleType;
use Tourze\CursorPorjectRules\ValueObject\ProjectRule;

class BaseRuleTest extends TestCase
{
    public function test_toProjectRule_conversion(): void
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

    public function test_isAlwaysApply_alwaysType(): void
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

    public function test_isAlwaysApply_otherTypes(): void
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

/**
 * 用于测试BaseRule的具体实现类
 */
class ConcreteRule extends BaseRule
{
    public function __construct(
        private string $name,
        private string $description,
        private RuleType $type,
        private array $globs = [],
        private string $content = '',
        private array $referencedFiles = []
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getType(): RuleType
    {
        return $this->type;
    }

    public function getGlobs(): array
    {
        return $this->globs;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getReferencedFiles(): array
    {
        return $this->referencedFiles;
    }
} 