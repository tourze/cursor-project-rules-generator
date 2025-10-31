<?php

namespace Tourze\CursorProjectRules\Factory;

use Tourze\CursorProjectRules\Exception\InvalidRuleTypeException;
use Tourze\CursorProjectRules\Model\Rule\BaseRule;
use Tourze\CursorProjectRules\Model\Rule\StyleRule;
use Tourze\CursorProjectRules\Model\Rule\TemplateRule;
use Tourze\CursorProjectRules\Model\Rule\WorkflowRule;

/**
 * 规则工厂类，用于创建不同类型的规则模型
 */
class RuleFactory
{
    /**
     * 创建样式规则
     *
     * @param string        $name            规则名称
     * @param string        $description     规则描述
     * @param array<string> $styleGuidelines 样式指南列表
     * @param array<string> $referencedFiles 引用的文件列表
     *
     * @return StyleRule 样式规则模型
     */
    public static function createStyleRule(
        string $name,
        string $description,
        array $styleGuidelines,
        array $referencedFiles = [],
    ): StyleRule {
        return new StyleRule($name, $description, $styleGuidelines, $referencedFiles);
    }

    /**
     * 创建模板规则
     *
     * @param string        $name            规则名称
     * @param string        $description     规则描述
     * @param array<string> $globs           文件匹配模式
     * @param array<string> $guidelines      模板指南列表
     * @param array<string> $referencedFiles 引用的文件列表
     *
     * @return TemplateRule 模板规则模型
     */
    public static function createTemplateRule(
        string $name,
        string $description,
        array $globs,
        array $guidelines,
        array $referencedFiles = [],
    ): TemplateRule {
        return new TemplateRule($name, $description, $globs, $guidelines, $referencedFiles);
    }

    /**
     * 创建工作流规则
     *
     * @param string        $name            规则名称
     * @param string        $description     规则描述
     * @param array<string> $steps           工作流步骤列表
     * @param array<string> $referencedFiles 引用的文件列表
     * @param bool          $alwaysApply     是否始终应用
     *
     * @return WorkflowRule 工作流规则模型
     */
    public static function createWorkflowRule(
        string $name,
        string $description,
        array $steps,
        array $referencedFiles = [],
        bool $alwaysApply = false,
    ): WorkflowRule {
        return new WorkflowRule($name, $description, $steps, $referencedFiles, $alwaysApply);
    }

    /**
     * 从数组创建规则模型
     *
     * @param array<string, mixed> $data 规则数据
     *
     * @return BaseRule 规则模型
     */
    public static function createFromArray(array $data): BaseRule
    {
        $type = self::ensureString($data['type'] ?? 'style');

        return match ($type) {
            'style' => self::createStyleRule(
                self::ensureString($data['name'] ?? 'unnamed-style-rule'),
                self::ensureString($data['description'] ?? 'Style rule'),
                self::ensureStringArray($data['guidelines'] ?? []),
                self::ensureStringArray($data['referencedFiles'] ?? [])
            ),
            'template' => self::createTemplateRule(
                self::ensureString($data['name'] ?? 'unnamed-template-rule'),
                self::ensureString($data['description'] ?? 'Template rule'),
                self::ensureStringArray($data['globs'] ?? []),
                self::ensureStringArray($data['guidelines'] ?? []),
                self::ensureStringArray($data['referencedFiles'] ?? [])
            ),
            'workflow' => self::createWorkflowRule(
                self::ensureString($data['name'] ?? 'unnamed-workflow-rule'),
                self::ensureString($data['description'] ?? 'Workflow rule'),
                self::ensureStringArray($data['steps'] ?? []),
                self::ensureStringArray($data['referencedFiles'] ?? []),
                (bool) ($data['alwaysApply'] ?? false)
            ),
            default => throw InvalidRuleTypeException::unknownType($type),
        };
    }

    /**
     * 确保值是字符串类型
     *
     * @param mixed $value 要转换的值
     *
     * @return string 字符串值
     */
    private static function ensureString(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_scalar($value) || (is_object($value) && method_exists($value, '__toString'))) {
            return (string) $value;
        }

        return '';
    }

    /**
     * 确保数组中的所有元素都是字符串
     *
     * @param mixed $value 要转换的值
     *
     * @return array<string> 字符串数组
     */
    private static function ensureStringArray(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }

        return array_map(
            static function ($item): string {
                return self::ensureString($item);
            },
            $value
        );
    }
}
