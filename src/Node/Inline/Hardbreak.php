<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Inline;

use Xen3r0\Adf\Enum\NodeType;
use Xen3r0\Adf\Node\InlineNode;

final class Hardbreak extends InlineNode
{
    public function getType(): string
    {
        return NodeType::HardBreak->value;
    }

    public static function fromArray(array $data): static
    {
        return new self();
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return ['type' => 'hardBreak'];
    }
}
