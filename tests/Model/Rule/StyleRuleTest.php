<?php

namespace Tourze\CursorProjectRules\Tests\Model\Rule;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\CursorProjectRules\Model\Rule\StyleRule;
use Tourze\CursorProjectRules\Model\RuleType;

/**
 * @internal
 */
#[CoversClass(StyleRule::class)]
final class StyleRuleTest extends TestCase
{
    public function testConstructorWithValidData(): void
    {
        $name = 'php-style';
        $description = 'PHP coding style';
        $guidelines = ['Use PSR-12', 'Use strong types'];
        $referencedFiles = ['style-guide.md'];

        $rule = new StyleRule($name, $description, $guidelines, $referencedFiles);

        $this->assertInstanceOf(StyleRule::class, $rule);
    }

    public function testGetNameCorrect(): void
    {
        $rule = new StyleRule('test-name', 'description', []);

        $this->assertEquals('test-name', $rule->getName());
    }

    public function testGetDescriptionCorrect(): void
    {
        $rule = new StyleRule('name', 'test-description', []);

        $this->assertEquals('test-description', $rule->getDescription());
    }

    public function testGetTypeAgentRequested(): void
    {
        $rule = new StyleRule('name', 'description', []);

        $this->assertEquals(RuleType::AGENT_REQUESTED, $rule->getType());
    }

    public function testGetContentWithGuidelines(): void
    {
        $guidelines = ['Use PSR-12', 'Use strong types', 'Add return types'];
        $rule = new StyleRule('name', 'description', $guidelines);

        $content = $rule->getContent();

        $this->assertStringContainsString('# 代码样式指南', $content);
        $this->assertStringContainsString('- Use PSR-12', $content);
        $this->assertStringContainsString('- Use strong types', $content);
        $this->assertStringContainsString('- Add return types', $content);
    }

    public function testGetContentEmptyGuidelines(): void
    {
        $rule = new StyleRule('name', 'description', []);

        $content = $rule->getContent();

        $this->assertEquals("# 代码样式指南\n\n", $content);
    }

    public function testGetReferencedFilesCorrect(): void
    {
        $referencedFiles = ['style-guide.md', 'examples.php'];
        $rule = new StyleRule('name', 'description', [], $referencedFiles);

        $this->assertEquals($referencedFiles, $rule->getReferencedFiles());
    }

    public function testGetGlobsEmpty(): void
    {
        $rule = new StyleRule('name', 'description', []);

        $this->assertEquals([], $rule->getGlobs());
    }
}
