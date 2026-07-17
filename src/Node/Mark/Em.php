<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Mark;

use Xen3r0\Adf\Enum\MarkType;

final class Em extends Mark
{
    public function getType(): string
    {
        return MarkType::Em->value;
    }

    public static function fromArray(array $data): static
    {
        return new self();
    }
}
