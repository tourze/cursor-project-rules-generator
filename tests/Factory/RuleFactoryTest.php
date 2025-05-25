<?php

namespace Tourze\CursorPorjectRules\Tests\Factory;

use PHPUnit\Framework\TestCase;
use Tourze\CursorPorjectRules\Factory\RuleFactory;
use Tourze\CursorPorjectRules\Model\Rule\StyleRule;
use Tourze\CursorPorjectRules\Model\Rule\TemplateRule;
use Tourze\CursorPorjectRules\Model\Rule\WorkflowRule;

class RuleFactoryTest extends TestCase
{
    public function test_createStyleRule_withValidData(): void
    {
        $name = 'php-style';
        $description = 'PHP coding style';
        $guidelines = ['Use PSR-12', 'Use strong types'];
        $referencedFiles = ['style-guide.md'];

        $rule = RuleFactory::createStyleRule($name, $description, $guidelines, $referencedFiles);

        $this->assertInstanceOf(StyleRule::class, $rule);
        $this->assertEquals($name, $rule->getName());
        $this->assertEquals($description, $rule->getDescription());
        $this->assertEquals($referencedFiles, $rule->getReferencedFiles());
    }

    public function test_createStyleRule_withEmptyData(): void
    {
        $name = '';
        $description = '';
        $guidelines = [];
        $referencedFiles = [];

        $rule = RuleFactory::createStyleRule($name, $description, $guidelines, $referencedFiles);

        $this->assertInstanceOf(StyleRule::class, $rule);
        $this->assertEquals('', $rule->getName());
        $this->assertEquals('', $rule->getDescription());
        $this->assertEquals([], $rule->getReferencedFiles());
        $this->assertEquals("# 代码样式指南\n\n", $rule->getContent());
    }

    public function test_createTemplateRule_withValidData(): void
    {
        $name = 'component-template';
        $description = 'Component template rules';
        $globs = ['*.tsx', 'components/*.tsx'];
        $guidelines = ['Use functional components', 'Use TypeScript'];
        $referencedFiles = ['template.tsx'];

        $rule = RuleFactory::createTemplateRule($name, $description, $globs, $guidelines, $referencedFiles);

        $this->assertInstanceOf(TemplateRule::class, $rule);
        $this->assertEquals($name, $rule->getName());
        $this->assertEquals($description, $rule->getDescription());
        $this->assertEquals($globs, $rule->getGlobs());
        $this->assertEquals($referencedFiles, $rule->getReferencedFiles());
    }

    public function test_createTemplateRule_withEmptyGlobs(): void
    {
        $name = 'empty-template';
        $description = 'Empty template rule';
        $globs = [];
        $guidelines = ['Some guideline'];

        $rule = RuleFactory::createTemplateRule($name, $description, $globs, $guidelines);

        $this->assertInstanceOf(TemplateRule::class, $rule);
        $this->assertEquals([], $rule->getGlobs());
        $this->assertEquals([], $rule->getReferencedFiles());
    }

    public function test_createWorkflowRule_withValidData(): void
    {
        $name = 'git-workflow';
        $description = 'Git workflow process';
        $steps = ['Create branch', 'Make changes', 'Create PR'];
        $referencedFiles = ['workflow.md'];
        $alwaysApply = false;

        $rule = RuleFactory::createWorkflowRule($name, $description, $steps, $referencedFiles, $alwaysApply);

        $this->assertInstanceOf(WorkflowRule::class, $rule);
        $this->assertEquals($name, $rule->getName());
        $this->assertEquals($description, $rule->getDescription());
        $this->assertEquals($referencedFiles, $rule->getReferencedFiles());
        $this->assertEquals($alwaysApply, $rule->isAlwaysApply());
    }

    public function test_createWorkflowRule_withAlwaysApply(): void
    {
        $name = 'mandatory-workflow';
        $description = 'Mandatory workflow';
        $steps = ['Always do this'];
        $alwaysApply = true;

        $rule = RuleFactory::createWorkflowRule($name, $description, $steps, [], $alwaysApply);

        $this->assertInstanceOf(WorkflowRule::class, $rule);
        $this->assertTrue($rule->isAlwaysApply());
    }

    public function test_createFromArray_styleType(): void
    {
        $data = [
            'type' => 'style',
            'name' => 'test-style',
            'description' => 'Test style rule',
            'guidelines' => ['Guideline 1', 'Guideline 2'],
            'referencedFiles' => ['file1.md']
        ];

        $rule = RuleFactory::createFromArray($data);

        $this->assertInstanceOf(StyleRule::class, $rule);
        $this->assertEquals('test-style', $rule->getName());
        $this->assertEquals('Test style rule', $rule->getDescription());
        $this->assertEquals(['file1.md'], $rule->getReferencedFiles());
    }

    public function test_createFromArray_templateType(): void
    {
        $data = [
            'type' => 'template',
            'name' => 'test-template',
            'description' => 'Test template rule',
            'globs' => ['*.js', '*.ts'],
            'guidelines' => ['Use ES6'],
            'referencedFiles' => ['template.js']
        ];

        $rule = RuleFactory::createFromArray($data);

        $this->assertInstanceOf(TemplateRule::class, $rule);
        $this->assertEquals('test-template', $rule->getName());
        $this->assertEquals('Test template rule', $rule->getDescription());
        $this->assertEquals(['*.js', '*.ts'], $rule->getGlobs());
        $this->assertEquals(['template.js'], $rule->getReferencedFiles());
    }

    public function test_createFromArray_workflowType(): void
    {
        $data = [
            'type' => 'workflow',
            'name' => 'test-workflow',
            'description' => 'Test workflow rule',
            'steps' => ['Step 1', 'Step 2'],
            'referencedFiles' => ['workflow.md'],
            'alwaysApply' => true
        ];

        $rule = RuleFactory::createFromArray($data);

        $this->assertInstanceOf(WorkflowRule::class, $rule);
        $this->assertEquals('test-workflow', $rule->getName());
        $this->assertEquals('Test workflow rule', $rule->getDescription());
        $this->assertEquals(['workflow.md'], $rule->getReferencedFiles());
        $this->assertTrue($rule->isAlwaysApply());
    }

    public function test_createFromArray_unknownType(): void
    {
        $data = [
            'type' => 'unknown',
            'name' => 'test-unknown'
        ];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('未知的规则类型: unknown');

        RuleFactory::createFromArray($data);
    }

    public function test_createFromArray_missingData(): void
    {
        $data = [
            'type' => 'style'
            // 缺少name、description等数据
        ];

        $rule = RuleFactory::createFromArray($data);

        $this->assertInstanceOf(StyleRule::class, $rule);
        $this->assertEquals('unnamed-style-rule', $rule->getName());
        $this->assertEquals('Style rule', $rule->getDescription());
        $this->assertEquals([], $rule->getReferencedFiles());
    }
} 