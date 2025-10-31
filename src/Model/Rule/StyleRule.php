<?php

namespace Tourze\CursorProjectRules\Model\Rule;

use Tourze\CursorProjectRules\Model\RuleType;

/**
 * 代码样式规则实现
 */
class StyleRule extends BaseRule
{
    /**
     * @param string        $name            规则名称
     * @param string        $description     规则描述
     * @param array<string> $styleGuidelines 样式指南列表
     * @param array<string> $referencedFiles 引用的文件列表
     */
    public function __construct(
        private string $name,
        private string $description,
        private array $styleGuidelines,
        private array $referencedFiles = [],
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
        return RuleType::AGENT_REQUESTED;
    }

    public function getContent(): string
    {
        $content = "# 代码样式指南\n\n";

        foreach ($this->styleGuidelines as $guideline) {
            $content .= '- ' . $guideline . "\n";
        }

        return $content;
    }

    public function getReferencedFiles(): array
    {
        return $this->referencedFiles;
    }
}
