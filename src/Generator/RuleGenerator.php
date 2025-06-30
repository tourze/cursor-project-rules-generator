<?php

namespace Tourze\CursorPorjectRules\Generator;

use Tourze\CursorPorjectRules\Exception\RuleFileNotFoundException;
use Tourze\CursorPorjectRules\Model\Rule\BaseRule;
use Tourze\CursorPorjectRules\ValueObject\ProjectRule;

/**
 * Cursor规则生成器
 */
class RuleGenerator
{
    /**
     * 规则文件的默认目录
     */
    private const DEFAULT_RULES_DIR = '.cursor/rules';

    /**
     * @param string $baseDir 项目基础目录
     * @param string $rulesDir 规则目录 (相对于baseDir)
     */
    public function __construct(
        private string $baseDir,
        private string $rulesDir = self::DEFAULT_RULES_DIR
    ) {
    }

    /**
     * 获取规则目录的完整路径
     *
     * @return string 规则目录的完整路径
     */
    public function getRulesPath(): string
    {
        return rtrim($this->baseDir, '/') . '/' . ltrim($this->rulesDir, '/');
    }

    /**
     * 确保规则目录存在
     */
    private function ensureRulesDirExists(): void
    {
        $rulesPath = $this->getRulesPath();

        if (!is_dir($rulesPath)) {
            mkdir($rulesPath, 0755, true);
        }
    }

    /**
     * 从规则模型生成规则文件
     *
     * @param BaseRule $rule 规则模型
     * @return string 生成的规则文件路径
     */
    public function generateFromRule(BaseRule $rule): string
    {
        return $this->generate($rule->toProjectRule());
    }

    /**
     * 从ProjectRule值对象生成规则文件
     *
     * @param ProjectRule $rule ProjectRule值对象
     * @return string 生成的规则文件路径
     */
    public function generate(ProjectRule $rule): string
    {
        $this->ensureRulesDirExists();
        
        $rulePath = $this->getRulesPath() . '/' . $rule->name . '.mdc';
        $ruleContent = $rule->toMDC();
        
        file_put_contents($rulePath, $ruleContent);
        
        return $rulePath;
    }

    /**
     * 从多个规则模型生成规则文件
     *
     * @param array<BaseRule> $rules 规则模型数组
     * @return array<string> 生成的规则文件路径数组
     */
    public function generateMultiple(array $rules): array
    {
        $generatedPaths = [];

        foreach ($rules as $rule) {
            $generatedPaths[] = $this->generateFromRule($rule);
        }

        return $generatedPaths;
    }

    /**
     * 读取规则文件内容并转换为ProjectRule对象
     *
     * @param string $rulePath 规则文件路径
     * @return ProjectRule 规则值对象
     */
    public function readRule(string $rulePath): ProjectRule
    {
        if (!file_exists($rulePath)) {
            throw RuleFileNotFoundException::fileNotFound($rulePath);
        }
        
        $ruleContent = file_get_contents($rulePath);
        $ruleName = pathinfo($rulePath, PATHINFO_FILENAME);
        
        return ProjectRule::fromMDC($ruleName, $ruleContent);
    }

    /**
     * 读取规则目录中的所有规则文件并转换为ProjectRule对象数组
     *
     * @return array<string, ProjectRule> 规则名称到规则值对象的映射
     */
    public function readAllRules(): array
    {
        $rulesPath = $this->getRulesPath();
        
        if (!is_dir($rulesPath)) {
            return [];
        }
        
        $rules = [];
        $mdcFiles = glob($rulesPath . '/*.mdc');
        
        foreach ($mdcFiles as $mdcFile) {
            $ruleName = pathinfo($mdcFile, PATHINFO_FILENAME);
            $rules[$ruleName] = $this->readRule($mdcFile);
        }
        
        return $rules;
    }
}
