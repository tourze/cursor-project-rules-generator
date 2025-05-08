<?php

namespace Tourze\CursorPorjectRules\Model;

/**
 * Cursor规则类型枚举
 */
enum RuleType: string
{
    /**
     * 始终包含在模型上下文中
     */
    case ALWAYS = 'always';

    /**
     * 当引用匹配glob模式的文件时包含
     */
    case AUTO_ATTACHED = 'auto_attached';

    /**
     * AI可用，AI决定是否包含
     */
    case AGENT_REQUESTED = 'agent_requested';

    /**
     * 仅在使用@ruleName显式提及时包含
     */
    case MANUAL = 'manual';
}
