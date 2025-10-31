<?php

namespace Tourze\CursorProjectRules\Model\Rule;

use Tourze\CursorProjectRules\Model\RuleType;

/**
 * 工作流规则实现
 */
class WorkflowRule extends BaseRule
{
    /**
     * @param string        $name            规则名称
     * @param string        $description     规则描述
     * @param array<string> $steps           工作流步骤列表
     * @param array<string> $referencedFiles 引用的文件列表
     * @param bool          $alwaysApply     是否始终应用
     */
    public function __construct(
        private string $name,
        private string $description,
        private array $steps,
        private array $referencedFiles = [],
        private bool $alwaysApply = false,
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
        return $this->alwaysApply ? RuleType::ALWAYS : RuleType::MANUAL;
    }

    public function isAlwaysApply(): bool
    {
        return $this->alwaysApply;
    }

    public function getContent(): string
    {
        $content = "# 工作流指南\n\n";

        for ($i = 0; $i < count($this->steps); ++$i) {
            $step = $this->steps[$i];
            $content .= ($i + 1) . '. ' . $step . "\n";
        }

        return $content;
    }

    public function getReferencedFiles(): array
    {
        return $this->referencedFiles;
    }
}
