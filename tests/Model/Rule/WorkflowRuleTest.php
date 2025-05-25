<?php

namespace Tourze\CursorPorjectRules\Tests\Model\Rule;

use PHPUnit\Framework\TestCase;
use Tourze\CursorPorjectRules\Model\Rule\WorkflowRule;
use Tourze\CursorPorjectRules\Model\RuleType;

class WorkflowRuleTest extends TestCase
{
    public function test_constructor_withValidData(): void
    {
        $name = 'git-workflow';
        $description = 'Git workflow process';
        $steps = ['Create branch', 'Make changes', 'Create PR'];
        $referencedFiles = ['workflow.md'];
        $alwaysApply = false;

        $rule = new WorkflowRule($name, $description, $steps, $referencedFiles, $alwaysApply);

        $this->assertInstanceOf(WorkflowRule::class, $rule);
    }

    public function test_getName_correct(): void
    {
        $rule = new WorkflowRule('test-name', 'description', []);

        $this->assertEquals('test-name', $rule->getName());
    }

    public function test_getDescription_correct(): void
    {
        $rule = new WorkflowRule('name', 'test-description', []);

        $this->assertEquals('test-description', $rule->getDescription());
    }

    public function test_getType_alwaysApplyTrue(): void
    {
        $rule = new WorkflowRule('name', 'description', [], [], true);

        $this->assertEquals(RuleType::ALWAYS, $rule->getType());
    }

    public function test_getType_alwaysApplyFalse(): void
    {
        $rule = new WorkflowRule('name', 'description', [], [], false);

        $this->assertEquals(RuleType::MANUAL, $rule->getType());
    }

    public function test_isAlwaysApply_correct(): void
    {
        $alwaysApplyTrue = new WorkflowRule('name', 'description', [], [], true);
        $alwaysApplyFalse = new WorkflowRule('name', 'description', [], [], false);

        $this->assertTrue($alwaysApplyTrue->isAlwaysApply());
        $this->assertFalse($alwaysApplyFalse->isAlwaysApply());
    }

    public function test_getContent_withSteps(): void
    {
        $steps = ['Create feature branch', 'Implement changes', 'Write tests', 'Create pull request'];
        $rule = new WorkflowRule('name', 'description', $steps);

        $content = $rule->getContent();

        $this->assertStringContainsString('# 工作流指南', $content);
        $this->assertStringContainsString('1. Create feature branch', $content);
        $this->assertStringContainsString('2. Implement changes', $content);
        $this->assertStringContainsString('3. Write tests', $content);
        $this->assertStringContainsString('4. Create pull request', $content);
    }

    public function test_getContent_emptySteps(): void
    {
        $rule = new WorkflowRule('name', 'description', []);

        $content = $rule->getContent();

        $this->assertEquals("# 工作流指南\n\n", $content);
    }

    public function test_getReferencedFiles_correct(): void
    {
        $referencedFiles = ['workflow.md', 'process.md'];
        $rule = new WorkflowRule('name', 'description', [], $referencedFiles);

        $this->assertEquals($referencedFiles, $rule->getReferencedFiles());
    }
} 