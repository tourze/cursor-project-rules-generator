<?php

namespace Tourze\CursorProjectRules\Tests\Generator;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\CursorProjectRules\Generator\RuleGenerator;
use Tourze\CursorProjectRules\Model\Rule\StyleRule;
use Tourze\CursorProjectRules\Model\RuleType;
use Tourze\CursorProjectRules\ValueObject\ProjectRule;

/**
 * @internal
 */
#[CoversClass(RuleGenerator::class)]
final class RuleGeneratorTest extends TestCase
{
    private string $tempDir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tempDir = sys_get_temp_dir() . '/cursor-rules-test-' . uniqid();
        mkdir($this->tempDir, 0o755, true);
    }

    protected function tearDown(): void
    {
        $this->removeDirectory($this->tempDir);
    }

    private function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->removeDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }

    public function testConstructorWithDefaultDir(): void
    {
        $generator = new RuleGenerator($this->tempDir);

        $expectedPath = $this->tempDir . '/.cursor/rules';
        $this->assertEquals($expectedPath, $generator->getRulesPath());
    }

    public function testConstructorWithCustomDir(): void
    {
        $customDir = 'custom/rules';
        $generator = new RuleGenerator($this->tempDir, $customDir);

        $expectedPath = $this->tempDir . '/' . $customDir;
        $this->assertEquals($expectedPath, $generator->getRulesPath());
    }

    public function testGetRulesPathDefaultDir(): void
    {
        $generator = new RuleGenerator($this->tempDir);

        $expectedPath = $this->tempDir . '/.cursor/rules';
        $this->assertEquals($expectedPath, $generator->getRulesPath());
    }

    public function testGetRulesPathCustomDir(): void
    {
        $generator = new RuleGenerator($this->tempDir, 'my-rules');

        $expectedPath = $this->tempDir . '/my-rules';
        $this->assertEquals($expectedPath, $generator->getRulesPath());
    }

    public function testGenerateCreatesFile(): void
    {
        $generator = new RuleGenerator($this->tempDir);

        $rule = new ProjectRule(
            'test-rule',
            'Test rule description',
            RuleType::MANUAL,
            [],
            false,
            'This is test content'
        );

        $filePath = $generator->generate($rule);

        $this->assertFileExists($filePath);
        $this->assertStringContainsString('test-rule.mdc', $filePath);

        $content = file_get_contents($filePath);
        $this->assertNotFalse($content);
        $this->assertStringContainsString('description: Test rule description', $content);
        $this->assertStringContainsString('This is test content', $content);
    }

    public function testGenerateCreatesDirectory(): void
    {
        $generator = new RuleGenerator($this->tempDir);

        $rulesDir = $generator->getRulesPath();
        $this->assertDirectoryDoesNotExist($rulesDir);

        $rule = new ProjectRule(
            'test-rule',
            'Test rule description',
            RuleType::MANUAL
        );

        $generator->generate($rule);

        $this->assertDirectoryExists($rulesDir);
    }

    public function testGenerateFromRuleSuccess(): void
    {
        $generator = new RuleGenerator($this->tempDir);

        $styleRule = new StyleRule(
            'php-style',
            'PHP coding style',
            ['Use PSR-12', 'Use strong types'],
            ['style-guide.md']
        );

        $filePath = $generator->generateFromRule($styleRule);

        $this->assertFileExists($filePath);
        $this->assertStringContainsString('php-style.mdc', $filePath);

        $content = file_get_contents($filePath);
        $this->assertNotFalse($content);
        $this->assertStringContainsString('PHP coding style', $content);
        $this->assertStringContainsString('Use PSR-12', $content);
        $this->assertStringContainsString('@style-guide.md', $content);
    }

    public function testGenerateMultipleSuccess(): void
    {
        $generator = new RuleGenerator($this->tempDir);

        $rule1 = new StyleRule('rule1', 'Rule 1', ['Guideline 1']);
        $rule2 = new StyleRule('rule2', 'Rule 2', ['Guideline 2']);

        $filePaths = $generator->generateMultiple([$rule1, $rule2]);

        $this->assertCount(2, $filePaths);
        $this->assertFileExists($filePaths[0]);
        $this->assertFileExists($filePaths[1]);
        $this->assertStringContainsString('rule1.mdc', $filePaths[0]);
        $this->assertStringContainsString('rule2.mdc', $filePaths[1]);
    }

    public function testReadRuleExistingFile(): void
    {
        $generator = new RuleGenerator($this->tempDir);

        // 先生成一个规则文件
        $originalRule = new ProjectRule(
            'test-rule',
            'Test description',
            RuleType::AGENT_REQUESTED,
            [],
            false,
            'Test content',
            ['test.md']
        );

        $filePath = $generator->generate($originalRule);

        // 读取规则文件
        $readRule = $generator->readRule($filePath);

        $this->assertEquals('test-rule', $readRule->name);
        $this->assertEquals('Test description', $readRule->description);
        $this->assertEquals(RuleType::AGENT_REQUESTED, $readRule->type);
        $this->assertEquals('Test content', $readRule->content);
        $this->assertEquals(['test.md'], $readRule->referencedFiles);
    }

    public function testReadRuleNonExistentFile(): void
    {
        $generator = new RuleGenerator($this->tempDir);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('规则文件不存在');

        $generator->readRule('/non/existent/file.mdc');
    }

    public function testReadAllRulesExistingDir(): void
    {
        $generator = new RuleGenerator($this->tempDir);

        // 生成多个规则文件
        $rule1 = new ProjectRule('rule1', 'Rule 1', RuleType::MANUAL, [], false, 'Content 1');
        $rule2 = new ProjectRule('rule2', 'Rule 2', RuleType::MANUAL, [], false, 'Content 2');

        $generator->generate($rule1);
        $generator->generate($rule2);

        $allRules = $generator->readAllRules();

        $this->assertCount(2, $allRules);
        $this->assertArrayHasKey('rule1', $allRules);
        $this->assertArrayHasKey('rule2', $allRules);
        $this->assertEquals('Rule 1', $allRules['rule1']->description);
        $this->assertEquals('Rule 2', $allRules['rule2']->description);
    }

    public function testReadAllRulesNonExistentDir(): void
    {
        $generator = new RuleGenerator($this->tempDir);

        $allRules = $generator->readAllRules();

        $this->assertEquals([], $allRules);
    }
}
