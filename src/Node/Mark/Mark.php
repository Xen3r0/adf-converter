<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Mark;

abstract class Mark implements \JsonSerializable
{
    abstract public function getType(): string;

    /**
     * @param array<string, mixed> $data
     */
    abstract public static function fromArray(array $data): static;

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $data = ['type' => $this->getType()];

        $attrs = $this->attrs();
        if ([] !== $attrs) {
            $data['attrs'] = $attrs;
        }

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    protected function attrs(): array
    {
        return [];
    }
}
