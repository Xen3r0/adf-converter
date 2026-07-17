<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Mark;

use Xen3r0\Adf\Enum\MarkType;

final class BackgroundColor extends Mark
{
    private string $color = '#ffffff';

    public function getType(): string
    {
        return MarkType::BackgroundColor->value;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    protected function attrs(): array
    {
        return ['color' => $this->color];
    }

    public static function fromArray(array $data): static
    {
        return (new self())->setColor((string) ($data['attrs']['color'] ?? '#ffffff'));
    }
}
