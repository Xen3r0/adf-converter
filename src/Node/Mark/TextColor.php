<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Mark;

use Xen3r0\Adf\Enum\MarkType;

final class TextColor extends Mark
{
    private string $color = '#000000';

    public function getType(): string
    {
        return MarkType::TextColor->value;
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
        return (new self())->setColor((string) ($data['attrs']['color'] ?? '#000000'));
    }
}
