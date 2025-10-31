<?php

namespace Tourze\CursorProjectRules\Exception;

final class InvalidMDCFormatException extends \InvalidArgumentException
{
    public static function invalidFormat(): self
    {
        return new self('无效的MDC内容格式');
    }
}
