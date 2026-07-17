<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Mark;

use Xen3r0\Adf\Enum\MarkType;

final class Subsup extends Mark
{
    private string $subsupType = 'sub';

    public function getType(): string
    {
        return MarkType::Subsup->value;
    }

    public function getSubsupType(): string
    {
        return $this->subsupType;
    }

    public function setSubsupType(string $subsupType): static
    {
        $this->subsupType = $subsupType;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    protected function attrs(): array
    {
        return ['type' => $this->subsupType];
    }

    public static function fromArray(array $data): static
    {
        return (new self())->setSubsupType((string) ($data['attrs']['type'] ?? 'sub'));
    }
}
