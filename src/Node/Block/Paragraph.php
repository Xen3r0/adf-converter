<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Block;

use Xen3r0\Adf\Enum\NodeType;
use Xen3r0\Adf\Node\BlockNode;

final class Paragraph extends BlockNode
{
    public function getType(): string
    {
        return NodeType::Paragraph->value;
    }

    public static function fromArray(array $data): static
    {
        return (new self())->setContent(self::contentFromArray($data));
    }
}
