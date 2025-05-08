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
}
