<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Mark;

use Xen3r0\Adf\Enum\MarkType;

final class Underline extends Mark
{
    public function getType(): string
    {
        return MarkType::Underline->value;
    }

    public static function fromArray(array $data): static
    {
        return new self();
    }
}
