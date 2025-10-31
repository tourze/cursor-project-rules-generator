<?php

namespace Tourze\CursorProjectRules\Tests\Model\Rule;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\CursorProjectRules\Model\Rule\WorkflowRule;
use Tourze\CursorProjectRules\Model\RuleType;

/**
 * @internal
 */
#[CoversClass(WorkflowRule::class)]
final class WorkflowRuleTest extends TestCase
{
    public function testConstructorWithValidData(): void
    {
        $name = 'git-workflow';
        $description = 'Git workflow process';
        $steps = ['Create branch', 'Make changes', 'Create PR'];
        $referencedFiles = ['workflow.md'];
        $alwaysApply = false;

        $rule = new WorkflowRule($name, $description, $steps, $referencedFiles, $alwaysApply);

        $this->assertInstanceOf(WorkflowRule::class, $rule);
    }

    public function testGetNameCorrect(): void
    {
        $rule = new WorkflowRule('test-name', 'description', []);

        $this->assertEquals('test-name', $rule->getName());
    }

    public function testGetDescriptionCorrect(): void
    {
        $rule = new WorkflowRule('name', 'test-description', []);

        $this->assertEquals('test-description', $rule->getDescription());
    }

    public function testGetTypeAlwaysApplyTrue(): void
    {
        $rule = new WorkflowRule('name', 'description', [], [], true);

        $this->assertEquals(RuleType::ALWAYS, $rule->getType());
    }

    public function testGetTypeAlwaysApplyFalse(): void
    {
        $rule = new WorkflowRule('name', 'description', [], [], false);

        $this->assertEquals(RuleType::MANUAL, $rule->getType());
    }

    public function testIsAlwaysApplyCorrect(): void
    {
        $alwaysApplyTrue = new WorkflowRule('name', 'description', [], [], true);
        $alwaysApplyFalse = new WorkflowRule('name', 'description', [], [], false);

        $this->assertTrue($alwaysApplyTrue->isAlwaysApply());
        $this->assertFalse($alwaysApplyFalse->isAlwaysApply());
    }

    public function testGetContentWithSteps(): void
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

    public function testGetContentEmptySteps(): void
    {
        $rule = new WorkflowRule('name', 'description', []);

        $content = $rule->getContent();

        $this->assertEquals("# 工作流指南\n\n", $content);
    }

    public function testGetReferencedFilesCorrect(): void
    {
        $referencedFiles = ['workflow.md', 'process.md'];
        $rule = new WorkflowRule('name', 'description', [], $referencedFiles);

        $this->assertEquals($referencedFiles, $rule->getReferencedFiles());
    }
}
