<?php

namespace Tourze\CursorPorjectRules\Tests\Model\Rule;

use PHPUnit\Framework\TestCase;
use Tourze\CursorPorjectRules\Model\Rule\StyleRule;
use Tourze\CursorPorjectRules\Model\RuleType;

class StyleRuleTest extends TestCase
{
    public function test_constructor_withValidData(): void
    {
        $name = 'php-style';
        $description = 'PHP coding style';
        $guidelines = ['Use PSR-12', 'Use strong types'];
        $referencedFiles = ['style-guide.md'];

        $rule = new StyleRule($name, $description, $guidelines, $referencedFiles);

        $this->assertInstanceOf(StyleRule::class, $rule);
    }

    public function test_getName_correct(): void
    {
        $rule = new StyleRule('test-name', 'description', []);

        $this->assertEquals('test-name', $rule->getName());
    }

    public function test_getDescription_correct(): void
    {
        $rule = new StyleRule('name', 'test-description', []);

        $this->assertEquals('test-description', $rule->getDescription());
    }

    public function test_getType_agentRequested(): void
    {
        $rule = new StyleRule('name', 'description', []);

        $this->assertEquals(RuleType::AGENT_REQUESTED, $rule->getType());
    }

    public function test_getContent_withGuidelines(): void
    {
        $guidelines = ['Use PSR-12', 'Use strong types', 'Add return types'];
        $rule = new StyleRule('name', 'description', $guidelines);

        $content = $rule->getContent();

        $this->assertStringContainsString('# 代码样式指南', $content);
        $this->assertStringContainsString('- Use PSR-12', $content);
        $this->assertStringContainsString('- Use strong types', $content);
        $this->assertStringContainsString('- Add return types', $content);
    }

    public function test_getContent_emptyGuidelines(): void
    {
        $rule = new StyleRule('name', 'description', []);

        $content = $rule->getContent();

        $this->assertEquals("# 代码样式指南\n\n", $content);
    }

    public function test_getReferencedFiles_correct(): void
    {
        $referencedFiles = ['style-guide.md', 'examples.php'];
        $rule = new StyleRule('name', 'description', [], $referencedFiles);

        $this->assertEquals($referencedFiles, $rule->getReferencedFiles());
    }

    public function test_getGlobs_empty(): void
    {
        $rule = new StyleRule('name', 'description', []);

        $this->assertEquals([], $rule->getGlobs());
    }
} 