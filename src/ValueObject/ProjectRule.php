<?php

namespace Tourze\CursorProjectRules\ValueObject;

use Tourze\CursorProjectRules\Exception\InvalidMDCFormatException;
use Tourze\CursorProjectRules\Model\RuleType;

/**
 * Cursor项目规则的值对象表示
 */
class ProjectRule
{
    /**
     * @param string        $name            规则名称
     * @param string        $description     规则描述
     * @param RuleType      $type            规则类型
     * @param array<string> $globs           文件匹配模式 (仅用于AUTO_ATTACHED类型)
     * @param bool          $alwaysApply     是否始终应用
     * @param string        $content         规则内容
     * @param array<string> $referencedFiles 引用的文件列表
     */
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly RuleType $type,
        public readonly array $globs = [],
        public readonly bool $alwaysApply = false,
        public readonly string $content = '',
        public readonly array $referencedFiles = [],
    ) {
    }

    /**
     * 将规则转换为MDC格式
     *
     * @return string MDC格式的规则内容
     */
    public function toMDC(): string
    {
        $mdcContent = "---\n";
        $mdcContent .= "description: {$this->description}\n";

        if ([] !== $this->globs) {
            $mdcContent .= "globs: \n";
            foreach ($this->globs as $glob) {
                $mdcContent .= "  - {$glob}\n";
            }
        } else {
            $mdcContent .= "globs: \n";
        }

        $mdcContent .= 'alwaysApply: ' . ($this->alwaysApply ? 'true' : 'false') . "\n";
        $mdcContent .= "---\n\n";

        // 添加规则内容
        $mdcContent .= $this->content;

        // 添加引用的文件
        if ([] !== $this->referencedFiles) {
            $mdcContent .= "\n\n";
            foreach ($this->referencedFiles as $file) {
                $mdcContent .= "@{$file}\n";
            }
        }

        return rtrim($mdcContent);
    }

    /**
     * 从MDC内容创建规则对象
     *
     * @param string $name       规则名称
     * @param string $mdcContent MDC格式的内容
     *
     * @return self 规则对象
     */
    public static function fromMDC(string $name, string $mdcContent): self
    {
        // 解析元数据和内容
        preg_match('/---\n(.*?)---\n(.*)/s', $mdcContent, $matches);

        if (count($matches) < 3) {
            throw InvalidMDCFormatException::invalidFormat();
        }

        $metadata = $matches[1];
        $content = $matches[2];

        // 解析描述
        preg_match('/description:\s*(.*?)\n/', $metadata, $descMatches);
        $description = $descMatches[1] ?? '';

        // 解析globs
        $globs = [];
        preg_match_all('/\s*-\s*(.*?)\n/', $metadata, $globMatches);
        if ([] !== $globMatches[1]) {
            $globs = $globMatches[1];
        }

        // 解析alwaysApply
        preg_match('/alwaysApply:\s*(true|false)/', $metadata, $applyMatches);
        $alwaysApply = ($applyMatches[1] ?? 'false') === 'true';

        // 确定规则类型
        if ($alwaysApply) {
            $type = RuleType::ALWAYS;
        } elseif ([] !== $globs) {
            $type = RuleType::AUTO_ATTACHED;
        } elseif ('' !== $description) {
            $type = RuleType::AGENT_REQUESTED;
        } else {
            $type = RuleType::MANUAL;
        }

        // 提取引用的文件
        $referencedFiles = [];
        preg_match_all('/^@([^\s\n]+)/m', $content, $fileMatches);
        if ([] !== $fileMatches[1]) {
            $referencedFiles = $fileMatches[1];

            // 从内容中移除文件引用部分，保留主要内容
            $mainContent = preg_replace('/^@([^\s\n]+)\n*/m', '', $content);
            if (null === $mainContent) {
                $mainContent = $content;
            }
        } else {
            $mainContent = $content;
        }

        return new self(
            $name,
            $description,
            $type,
            $globs,
            $alwaysApply,
            trim($mainContent),
            $referencedFiles
        );
    }
}
