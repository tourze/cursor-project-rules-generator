<?php

namespace Tourze\CursorPorjectRules\Model;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * Cursor规则类型枚举
 */
enum RuleType: string implements Itemable, Labelable, Selectable
{
    use ItemTrait;
    use SelectTrait;

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

    public function getLabel(): string
    {
        return match ($this) {
            self::ALWAYS => '始终包含',
            self::AUTO_ATTACHED => '自动附加',
            self::AGENT_REQUESTED => 'AI请求',
            self::MANUAL => '手动指定',
        };
    }
}
