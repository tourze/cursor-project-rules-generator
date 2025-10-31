<?php

namespace Tourze\CursorProjectRules\Model\Rule;

use Tourze\CursorProjectRules\Model\RuleType;

/**
 * 代码模板规则实现
 */
class TemplateRule extends BaseRule
{
    /**
     * @param string        $name            规则名称
     * @param string        $description     规则描述
     * @param array<string> $globs           文件匹配模式
     * @param array<string> $guidelines      模板指南列表
     * @param array<string> $referencedFiles 引用的文件列表
     */
    public function __construct(
        private string $name,
        private string $description,
        private array $globs,
        private array $guidelines,
        private array $referencedFiles,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getType(): RuleType
    {
        return RuleType::AUTO_ATTACHED;
    }

    public function getGlobs(): array
    {
        return $this->globs;
    }

    public function getContent(): string
    {
        $content = "# 代码模板指南\n\n";

        foreach ($this->guidelines as $guideline) {
            $content .= '- ' . $guideline . "\n";
        }

        return $content;
    }

    public function getReferencedFiles(): array
    {
        return $this->referencedFiles;
    }
}
