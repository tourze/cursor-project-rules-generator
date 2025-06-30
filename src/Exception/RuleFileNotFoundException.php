<?php

namespace Tourze\CursorPorjectRules\Exception;

final class RuleFileNotFoundException extends \InvalidArgumentException
{
    public static function fileNotFound(string $path): self
    {
        return new self("规则文件不存在: {$path}");
    }
}