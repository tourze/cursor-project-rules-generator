<?php

namespace Tourze\CursorPorjectRules\Tests\ValueObject;

use PHPUnit\Framework\TestCase;
use Tourze\CursorPorjectRules\Model\RuleType;
use Tourze\CursorPorjectRules\ValueObject\ProjectRule;

class ProjectRuleTest extends TestCase
{
    public function testToMDC(): void
    {
        $rule = new ProjectRule(
            'test-rule',
            'Test rule description',
            RuleType::AGENT_REQUESTED,
            [],
            false,
            'This is the rule content',
            ['example.php']
        );

        $expected = <<<EOT
---
description: Test rule description
globs: 
alwaysApply: false
---

This is the rule content

@example.php


EOT;

        $this->assertEquals($expected, $rule->toMDC());
    }

    public function testToMDCWithGlobs(): void
    {
        $rule = new ProjectRule(
            'test-rule',
            'Test rule description',
            RuleType::AUTO_ATTACHED,
            ['*.php', 'src/*.ts'],
            false,
            'This is the rule content',
            []
        );

        $expected = <<<EOT
---
description: Test rule description
globs: 
  - *.php
  - src/*.ts
alwaysApply: false
---

This is the rule content


EOT;

        $this->assertEquals($expected, $rule->toMDC());
    }

    public function testFromMDC(): void
    {
        $mdcContent = <<<EOT
---
description: Test rule description
globs: 
alwaysApply: false
---

This is the rule content

@example.php

EOT;

        $rule = ProjectRule::fromMDC('test-rule', $mdcContent);

        $this->assertEquals('test-rule', $rule->name);
        $this->assertEquals('Test rule description', $rule->description);
        $this->assertEquals(RuleType::AGENT_REQUESTED, $rule->type);
        $this->assertEquals([], $rule->globs);
        $this->assertFalse($rule->alwaysApply);
        $this->assertEquals('This is the rule content', $rule->content);
        $this->assertEquals(['example.php'], $rule->referencedFiles);
    }

    public function testFromMDCWithGlobs(): void
    {
        $mdcContent = <<<EOT
---
description: Test rule description
globs: 
  - *.php
  - src/*.ts
alwaysApply: false
---

This is the rule content

EOT;

        $rule = ProjectRule::fromMDC('test-rule', $mdcContent);

        $this->assertEquals('test-rule', $rule->name);
        $this->assertEquals('Test rule description', $rule->description);
        $this->assertEquals(RuleType::AUTO_ATTACHED, $rule->type);
        $this->assertEquals(['*.php', 'src/*.ts'], $rule->globs);
        $this->assertFalse($rule->alwaysApply);
        $this->assertEquals('This is the rule content', $rule->content);
        $this->assertEquals([], $rule->referencedFiles);
    }

    public function testFromMDCWithAlwaysApply(): void
    {
        $mdcContent = <<<EOT
---
description: Test rule description
globs: 
alwaysApply: true
---

This is the rule content

EOT;

        $rule = ProjectRule::fromMDC('test-rule', $mdcContent);

        $this->assertEquals('test-rule', $rule->name);
        $this->assertEquals('Test rule description', $rule->description);
        $this->assertEquals(RuleType::ALWAYS, $rule->type);
        $this->assertEquals([], $rule->globs);
        $this->assertTrue($rule->alwaysApply);
        $this->assertEquals('This is the rule content', $rule->content);
        $this->assertEquals([], $rule->referencedFiles);
    }

    public function testInvalidMDCFormat(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('无效的MDC内容格式');

        ProjectRule::fromMDC('test-rule', 'Invalid MDC content');
    }

    public function test_constructor_withAllParameters(): void
    {
        $rule = new ProjectRule(
            'test-rule',
            'Test description',
            RuleType::AUTO_ATTACHED,
            ['*.php', '*.ts'],
            true,
            'Test content with details',
            ['file1.md', 'file2.php']
        );

        $this->assertEquals('test-rule', $rule->name);
        $this->assertEquals('Test description', $rule->description);
        $this->assertEquals(RuleType::AUTO_ATTACHED, $rule->type);
        $this->assertEquals(['*.php', '*.ts'], $rule->globs);
        $this->assertTrue($rule->alwaysApply);
        $this->assertEquals('Test content with details', $rule->content);
        $this->assertEquals(['file1.md', 'file2.php'], $rule->referencedFiles);
    }

    public function test_readonly_properties(): void
    {
        $rule = new ProjectRule(
            'readonly-test',
            'Readonly test',
            RuleType::MANUAL
        );

        // 验证属性是只读的（PHP 8.1+ readonly 属性）
        $this->assertEquals('readonly-test', $rule->name);
        $this->assertEquals('Readonly test', $rule->description);
        $this->assertEquals(RuleType::MANUAL, $rule->type);
        $this->assertEquals([], $rule->globs);
        $this->assertFalse($rule->alwaysApply);
        $this->assertEquals('', $rule->content);
        $this->assertEquals([], $rule->referencedFiles);
    }

    public function test_toMDC_withMultipleReferencedFiles(): void
    {
        $rule = new ProjectRule(
            'multi-ref-rule',
            'Rule with multiple references',
            RuleType::MANUAL,
            [],
            false,
            'This rule references multiple files',
            ['file1.md', 'file2.php', 'file3.js']
        );

        $mdcContent = $rule->toMDC();

        $this->assertStringContainsString('@file1.md', $mdcContent);
        $this->assertStringContainsString('@file2.php', $mdcContent);
        $this->assertStringContainsString('@file3.js', $mdcContent);
    }

    public function test_fromMDC_complexContent(): void
    {
        $mdcContent = <<<EOT
---
description: Complex rule with various elements
globs: 
  - *.php
  - src/**/*.ts
  - tests/**/*.php
alwaysApply: true
---

# Complex Rule

This is a complex rule with:
- Multiple lines
- Special characters: @#$%^&*()
- Unicode characters: 中文字符
- Code blocks

```php
<?php
echo "Hello, World!";
```

@complex-example.php
@documentation.md

EOT;

        $rule = ProjectRule::fromMDC('complex-rule', $mdcContent);

        $this->assertEquals('complex-rule', $rule->name);
        $this->assertEquals('Complex rule with various elements', $rule->description);
        $this->assertEquals(RuleType::ALWAYS, $rule->type);
        $this->assertEquals(['*.php', 'src/**/*.ts', 'tests/**/*.php'], $rule->globs);
        $this->assertTrue($rule->alwaysApply);
        $this->assertStringContainsString('# Complex Rule', $rule->content);
        $this->assertStringContainsString('Multiple lines', $rule->content);
        $this->assertStringContainsString('中文字符', $rule->content);
        $this->assertStringContainsString('```php', $rule->content);
        $this->assertEquals(['complex-example.php', 'documentation.md'], $rule->referencedFiles);
    }
}
