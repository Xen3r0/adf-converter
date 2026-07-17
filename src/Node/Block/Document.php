<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Block;

use Xen3r0\Adf\Enum\NodeType;
use Xen3r0\Adf\Node\BlockNode;

final class Document extends BlockNode
{
    private int $version = 1;

    public function getType(): string
    {
        return NodeType::Document->value;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function setVersion(int $version): static
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Convenience alias for fromArray(), matching the shape of the raw ADF
     * document you get back from the Jira API.
     *
     * @param array<string, mixed> $data
     */
    public static function load(array $data): self
    {
        return self::fromArray($data);
    }

    public static function fromArray(array $data): static
    {
        $document = (new self())->setContent(self::contentFromArray($data));
        $document->setVersion((int) ($data['version'] ?? 1));

        return $document;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'version' => $this->version,
            'type' => 'doc',
            'content' => $this->getContent(),
        ];
    }
}
