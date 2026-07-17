<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node;

abstract class Node implements \JsonSerializable
{
    abstract public function getType(): string;

    /**
     * @param array<string, mixed> $data
     */
    abstract public static function fromArray(array $data): static;

    /**
     * Fully recursive plain-array representation of this node.
     *
     * jsonSerialize() only serializes one level at a time (as required by the
     * JsonSerializable interface); go through json_encode()/json_decode() here
     * so callers get a plain, fully-recursive array back.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return json_decode((string) json_encode($this), true);
    }
}
