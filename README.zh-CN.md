# Cursor Project Rules Generator

[English](README.md) | [中文](README.zh-CN.md)

这个 PHP 库提供了用于创建和管理 Cursor 项目规则的工具，让你能够以编程方式生成 `.cursor/rules` 目录中的 MDC 格式规则文件。

## 功能特性

- 生成符合 Cursor 规则格式的 MDC 文件
- 支持多种规则类型：Always、Auto Attached、Agent Requested、Manual
- 提供值对象和模型对象两种表示方式
- 包含规则读取和写入功能
- 支持引用外部文件

## 安装

```bash
composer require tourze/cursor-project-rules-generator
```

## 快速开始

```php
<?php

use Tourze\CursorProjectRules\Factory\RuleFactory;
use Tourze\CursorProjectRules\Generator\RuleGenerator;

// 创建规则生成器
$generator = new RuleGenerator('/path/to/your/project');

// 创建一个简单的样式规则
$styleRule = RuleFactory::createStyleRule(
    'php-style',
    'PHP代码样式指南',
    [
        '使用PSR-12代码风格',
        '使用强类型声明',
        '优先使用命名空间导入',
    ]
);

// 生成规则文件
$generator->generateFromRule($styleRule);
```

## 基本用法

### 创建和生成规则

```php
<?php

use Tourze\CursorProjectRules\Factory\RuleFactory;
use Tourze\CursorProjectRules\Generator\RuleGenerator;

// 创建样式规则
$styleRule = RuleFactory::createStyleRule(
    'php-style',
    'PHP代码样式指南',
    [
        '使用PSR-12代码风格',
        '使用强类型声明',
        '优先使用命名空间导入',
    ]
);

// 创建模板规则
$templateRule = RuleFactory::createTemplateRule(
    'component-template',
    '组件模板规则',
    ['*.tsx', 'components/*.tsx'],
    [
        '使用函数组件',
        '使用TypeScript类型',
        '遵循项目命名约定',
    ],
    ['component-example.tsx']
);

// 创建工作流规则
$workflowRule = RuleFactory::createWorkflowRule(
    'git-workflow',
    'Git工作流程',
    [
        '创建特性分支',
        '提交前运行测试',
        '使用语义化提交消息',
        '创建拉取请求进行代码审查',
    ],
    [],
    true
);

// 初始化规则生成器并生成规则文件
$generator = new RuleGenerator('/path/to/your/project');
$generator->generateFromRule($styleRule);
$generator->generateFromRule($templateRule);
$generator->generateFromRule($workflowRule);

// 或者一次性生成多个规则
$generator->generateMultiple([$styleRule, $templateRule, $workflowRule]);
```

### 读取规则文件

```php
<?php

use Tourze\CursorProjectRules\Generator\RuleGenerator;

$generator = new RuleGenerator('/path/to/your/project');

// 读取单个规则
$rule = $generator->readRule('/path/to/your/project/.cursor/rules/php-style.mdc');

// 读取所有规则
$allRules = $generator->readAllRules();
```

### 直接使用ProjectRule值对象

```php
<?php

use Tourze\CursorProjectRules\Model\RuleType;
use Tourze\CursorProjectRules\ValueObject\ProjectRule;

// 创建规则值对象
$rule = new ProjectRule(
    'code-review',
    '代码审查指南',
    RuleType::AGENT_REQUESTED,
    [],
    false,
    '# 代码审查指南\n\n- 关注安全问题\n- 检查边界条件\n- 验证错误处理',
    ['review-checklist.md']
);

// 生成MDC格式内容
$mdcContent = $rule->toMDC();

// 将MDC内容写入文件
file_put_contents('/path/to/your/project/.cursor/rules/code-review.mdc', $mdcContent);
```

## 参考文档

- [Cursor Rules 文档](https://docs.cursor.com/context/rules)

## 许可证

MIT 许可证。详情请参阅 [许可证文件](LICENSE)。
