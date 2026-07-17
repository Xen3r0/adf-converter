<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Child;

use Xen3r0\Adf\Enum\NodeType;
use Xen3r0\Adf\Node\BlockNode;

final class ListItem extends BlockNode
{
    public function getType(): string
    {
        return NodeType::ListItem->value;
    }

    public static function fromArray(array $data): static
    {
        return (new self())->setContent(self::contentFromArray($data));
    }
}
