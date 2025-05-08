<?php

namespace Tourze\CursorPorjectRules\Factory;

use Tourze\CursorPorjectRules\Model\Rule\BaseRule;
use Tourze\CursorPorjectRules\Model\Rule\StyleRule;
use Tourze\CursorPorjectRules\Model\Rule\TemplateRule;
use Tourze\CursorPorjectRules\Model\Rule\WorkflowRule;

/**
 * 规则工厂类，用于创建不同类型的规则模型
 */
class RuleFactory
{
    /**
     * 创建样式规则
     * 
     * @param string $name 规则名称
     * @param string $description 规则描述
     * @param array<string> $styleGuidelines 样式指南列表
     * @param array<string> $referencedFiles 引用的文件列表
     * @return StyleRule 样式规则模型
     */
    public static function createStyleRule(
        string $name,
        string $description,
        array $styleGuidelines,
        array $referencedFiles = []
    ): StyleRule {
        return new StyleRule($name, $description, $styleGuidelines, $referencedFiles);
    }
    
    /**
     * 创建模板规则
     * 
     * @param string $name 规则名称
     * @param string $description 规则描述
     * @param array<string> $globs 文件匹配模式
     * @param array<string> $guidelines 模板指南列表
     * @param array<string> $referencedFiles 引用的文件列表
     * @return TemplateRule 模板规则模型
     */
    public static function createTemplateRule(
        string $name,
        string $description,
        array $globs,
        array $guidelines,
        array $referencedFiles = []
    ): TemplateRule {
        return new TemplateRule($name, $description, $globs, $guidelines, $referencedFiles);
    }
    
    /**
     * 创建工作流规则
     * 
     * @param string $name 规则名称
     * @param string $description 规则描述
     * @param array<string> $steps 工作流步骤列表
     * @param array<string> $referencedFiles 引用的文件列表
     * @param bool $alwaysApply 是否始终应用
     * @return WorkflowRule 工作流规则模型
     */
    public static function createWorkflowRule(
        string $name,
        string $description,
        array $steps,
        array $referencedFiles = [],
        bool $alwaysApply = false
    ): WorkflowRule {
        return new WorkflowRule($name, $description, $steps, $referencedFiles, $alwaysApply);
    }
    
    /**
     * 从数组创建规则模型
     * 
     * @param array<string, mixed> $data 规则数据
     * @return BaseRule 规则模型
     */
    public static function createFromArray(array $data): BaseRule
    {
        $type = $data['type'] ?? 'style';
        
        return match ($type) {
            'style' => self::createStyleRule(
                $data['name'] ?? 'unnamed-style-rule',
                $data['description'] ?? 'Style rule',
                $data['guidelines'] ?? [],
                $data['referencedFiles'] ?? []
            ),
            'template' => self::createTemplateRule(
                $data['name'] ?? 'unnamed-template-rule',
                $data['description'] ?? 'Template rule',
                $data['globs'] ?? [],
                $data['guidelines'] ?? [],
                $data['referencedFiles'] ?? []
            ),
            'workflow' => self::createWorkflowRule(
                $data['name'] ?? 'unnamed-workflow-rule',
                $data['description'] ?? 'Workflow rule',
                $data['steps'] ?? [],
                $data['referencedFiles'] ?? [],
                $data['alwaysApply'] ?? false
            ),
            default => throw new \InvalidArgumentException("未知的规则类型: {$type}")
        };
    }
}
