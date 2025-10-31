<?php

namespace Tourze\CursorProjectRules\Model\Rule;

use Tourze\CursorProjectRules\Model\RuleType;
use Tourze\CursorProjectRules\ValueObject\ProjectRule;

/**
 * 所有具体规则模型的基类
 */
abstract class BaseRule
{
    /**
     * 获取规则名称
     *
     * @return string 规则名称
     */
    abstract public function getName(): string;

    /**
     * 获取规则描述
     *
     * @return string 规则描述
     */
    abstract public function getDescription(): string;

    /**
     * 获取规则类型
     *
     * @return RuleType 规则类型
     */
    abstract public function getType(): RuleType;

    /**
     * 获取文件匹配模式 (仅用于AUTO_ATTACHED类型)
     *
     * @return array<string> 文件匹配模式
     */
    public function getGlobs(): array
    {
        return [];
    }

    /**
     * 获取规则是否始终应用
     *
     * @return bool 是否始终应用
     */
    public function isAlwaysApply(): bool
    {
        return RuleType::ALWAYS === $this->getType();
    }

    /**
     * 获取规则内容
     *
     * @return string 规则内容
     */
    abstract public function getContent(): string;

    /**
     * 获取引用的文件列表
     *
     * @return array<string> 引用的文件列表
     */
    public function getReferencedFiles(): array
    {
        return [];
    }

    /**
     * 将规则模型转换为ProjectRule值对象
     *
     * @return ProjectRule 规则值对象
     */
    public function toProjectRule(): ProjectRule
    {
        return new ProjectRule(
            $this->getName(),
            $this->getDescription(),
            $this->getType(),
            $this->getGlobs(),
            $this->isAlwaysApply(),
            $this->getContent(),
            $this->getReferencedFiles()
        );
    }
}
