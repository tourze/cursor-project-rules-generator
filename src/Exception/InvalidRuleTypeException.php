<?php

namespace Tourze\CursorPorjectRules\Exception;

final class InvalidRuleTypeException extends \InvalidArgumentException
{
    public static function unknownType(string $type): self
    {
        return new self("未知的规则类型: {$type}");
    }
}