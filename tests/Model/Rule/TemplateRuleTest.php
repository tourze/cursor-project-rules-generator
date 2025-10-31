<?php

namespace Tourze\CursorProjectRules\Tests\Model\Rule;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\CursorProjectRules\Model\Rule\TemplateRule;
use Tourze\CursorProjectRules\Model\RuleType;

/**
 * @internal
 */
#[CoversClass(TemplateRule::class)]
final class TemplateRuleTest extends TestCase
{
    public function testConstructorWithValidData(): void
    {
        $name = 'component-template';
        $description = 'Component template rules';
        $globs = ['*.tsx', 'components/*.tsx'];
        $guidelines = ['Use functional components', 'Use TypeScript'];
        $referencedFiles = ['template.tsx'];

        $rule = new TemplateRule($name, $description, $globs, $guidelines, $referencedFiles);

        $this->assertInstanceOf(TemplateRule::class, $rule);
    }

    public function testGetNameCorrect(): void
    {
        $rule = new TemplateRule('test-name', 'description', [], [], []);

        $this->assertEquals('test-name', $rule->getName());
    }

    public function testGetDescriptionCorrect(): void
    {
        $rule = new TemplateRule('name', 'test-description', [], [], []);

        $this->assertEquals('test-description', $rule->getDescription());
    }

    public function testGetTypeAutoAttached(): void
    {
        $rule = new TemplateRule('name', 'description', [], [], []);

        $this->assertEquals(RuleType::AUTO_ATTACHED, $rule->getType());
    }

    public function testGetGlobsCorrect(): void
    {
        $globs = ['*.tsx', 'components/*.tsx', 'pages/*.tsx'];
        $rule = new TemplateRule('name', 'description', $globs, [], []);

        $this->assertEquals($globs, $rule->getGlobs());
    }

    public function testGetContentWithGuidelines(): void
    {
        $guidelines = ['Use functional components', 'Use TypeScript', 'Add prop types'];
        $rule = new TemplateRule('name', 'description', [], $guidelines, []);

        $content = $rule->getContent();

        $this->assertStringContainsString('# 代码模板指南', $content);
        $this->assertStringContainsString('- Use functional components', $content);
        $this->assertStringContainsString('- Use TypeScript', $content);
        $this->assertStringContainsString('- Add prop types', $content);
    }

    public function testGetContentEmptyGuidelines(): void
    {
        $rule = new TemplateRule('name', 'description', [], [], []);

        $content = $rule->getContent();

        $this->assertEquals("# 代码模板指南\n\n", $content);
    }

    public function testGetReferencedFilesCorrect(): void
    {
        $referencedFiles = ['template.tsx', 'example.tsx'];
        $rule = new TemplateRule('name', 'description', [], [], $referencedFiles);

        $this->assertEquals($referencedFiles, $rule->getReferencedFiles());
    }
}
