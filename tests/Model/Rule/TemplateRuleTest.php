<?php

namespace Tourze\CursorPorjectRules\Tests\Model\Rule;

use PHPUnit\Framework\TestCase;
use Tourze\CursorPorjectRules\Model\Rule\TemplateRule;
use Tourze\CursorPorjectRules\Model\RuleType;

class TemplateRuleTest extends TestCase
{
    public function test_constructor_withValidData(): void
    {
        $name = 'component-template';
        $description = 'Component template rules';
        $globs = ['*.tsx', 'components/*.tsx'];
        $guidelines = ['Use functional components', 'Use TypeScript'];
        $referencedFiles = ['template.tsx'];

        $rule = new TemplateRule($name, $description, $globs, $guidelines, $referencedFiles);

        $this->assertInstanceOf(TemplateRule::class, $rule);
    }

    public function test_getName_correct(): void
    {
        $rule = new TemplateRule('test-name', 'description', [], [], []);

        $this->assertEquals('test-name', $rule->getName());
    }

    public function test_getDescription_correct(): void
    {
        $rule = new TemplateRule('name', 'test-description', [], [], []);

        $this->assertEquals('test-description', $rule->getDescription());
    }

    public function test_getType_autoAttached(): void
    {
        $rule = new TemplateRule('name', 'description', [], [], []);

        $this->assertEquals(RuleType::AUTO_ATTACHED, $rule->getType());
    }

    public function test_getGlobs_correct(): void
    {
        $globs = ['*.tsx', 'components/*.tsx', 'pages/*.tsx'];
        $rule = new TemplateRule('name', 'description', $globs, [], []);

        $this->assertEquals($globs, $rule->getGlobs());
    }

    public function test_getContent_withGuidelines(): void
    {
        $guidelines = ['Use functional components', 'Use TypeScript', 'Add prop types'];
        $rule = new TemplateRule('name', 'description', [], $guidelines, []);

        $content = $rule->getContent();

        $this->assertStringContainsString('# 代码模板指南', $content);
        $this->assertStringContainsString('- Use functional components', $content);
        $this->assertStringContainsString('- Use TypeScript', $content);
        $this->assertStringContainsString('- Add prop types', $content);
    }

    public function test_getContent_emptyGuidelines(): void
    {
        $rule = new TemplateRule('name', 'description', [], [], []);

        $content = $rule->getContent();

        $this->assertEquals("# 代码模板指南\n\n", $content);
    }

    public function test_getReferencedFiles_correct(): void
    {
        $referencedFiles = ['template.tsx', 'example.tsx'];
        $rule = new TemplateRule('name', 'description', [], [], $referencedFiles);

        $this->assertEquals($referencedFiles, $rule->getReferencedFiles());
    }
} 