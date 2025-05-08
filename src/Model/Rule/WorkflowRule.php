<?php

namespace Tourze\CursorPorjectRules\Model\Rule;

use Tourze\CursorPorjectRules\Model\RuleType;

/**
 * 工作流规则实现
 */
class WorkflowRule extends BaseRule
{
    /**
     * @param string $name 规则名称
     * @param string $description 规则描述
     * @param array<string> $steps 工作流步骤列表
     * @param array<string> $referencedFiles 引用的文件列表
     * @param bool $alwaysApply 是否始终应用
     */
    public function __construct(
        private string $name,
        private string $description,
        private array $steps,
        private array $referencedFiles = [],
        private bool $alwaysApply = false
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @inheritDoc
     */
    public function getType(): RuleType
    {
        return $this->alwaysApply ? RuleType::ALWAYS : RuleType::MANUAL;
    }

    /**
     * @inheritDoc
     */
    public function isAlwaysApply(): bool
    {
        return $this->alwaysApply;
    }

    /**
     * @inheritDoc
     */
    public function getContent(): string
    {
        $content = "# 工作流指南\n\n";

        for ($i = 0; $i < count($this->steps); $i++) {
            $step = $this->steps[$i];
            $content .= ($i + 1) . ". " . $step . "\n";
        }

        return $content;
    }

    /**
     * @inheritDoc
     */
    public function getReferencedFiles(): array
    {
        return $this->referencedFiles;
    }
}
