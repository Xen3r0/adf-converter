<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Block;

use Xen3r0\Adf\Enum\NodeType;
use Xen3r0\Adf\Node\BlockNode;

final class CodeBlock extends BlockNode
{
    private ?string $language = null;

    public function getType(): string
    {
        return NodeType::CodeBlock->value;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): static
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    protected function attrs(): array
    {
        return null !== $this->language ? ['language' => $this->language] : [];
    }

    public static function fromArray(array $data): static
    {
        $node = (new self())->setContent(self::contentFromArray($data));

        if (isset($data['attrs']['language'])) {
            $node->setLanguage((string) $data['attrs']['language']);
        }

        return $node;
    }
}
