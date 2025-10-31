<?php

namespace Tourze\CursorProjectRules\Tests\Model\Rule;

use Tourze\CursorProjectRules\Model\Rule\BaseRule;
use Tourze\CursorProjectRules\Model\RuleType;

class ConcreteRule extends BaseRule
{
    public function __construct(
        private string $name,
        private string $description,
        private RuleType $type,
        /** @var array<string> */
        private array $globs = [],
        private string $content = '',
        /** @var array<string> */
        private array $referencedFiles = [],
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getType(): RuleType
    {
        return $this->type;
    }

    public function getGlobs(): array
    {
        return $this->globs;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getReferencedFiles(): array
    {
        return $this->referencedFiles;
    }
}
