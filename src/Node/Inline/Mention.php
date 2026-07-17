<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Inline;

use Xen3r0\Adf\Enum\NodeType;
use Xen3r0\Adf\Node\InlineNode;

final class Mention extends InlineNode
{
    private string $id = '';
    private ?string $text = null;
    private ?string $userType = null;
    private ?string $accessLevel = null;

    public function getType(): string
    {
        return NodeType::Mention->value;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getUserType(): ?string
    {
        return $this->userType;
    }

    public function setUserType(?string $userType): static
    {
        $this->userType = $userType;

        return $this;
    }

    public function getAccessLevel(): ?string
    {
        return $this->accessLevel;
    }

    public function setAccessLevel(?string $accessLevel): static
    {
        $this->accessLevel = $accessLevel;

        return $this;
    }

    public static function fromArray(array $data): static
    {
        $node = (new self())->setId((string) ($data['attrs']['id'] ?? ''));

        if (isset($data['attrs']['text'])) {
            $node->setText((string) $data['attrs']['text']);
        }

        if (isset($data['attrs']['userType'])) {
            $node->setUserType((string) $data['attrs']['userType']);
        }

        if (isset($data['attrs']['accessLevel'])) {
            $node->setAccessLevel((string) $data['attrs']['accessLevel']);
        }

        return $node;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $attrs = ['id' => $this->id];

        if (null !== $this->text) {
            $attrs['text'] = $this->text;
        }

        if (null !== $this->userType) {
            $attrs['userType'] = $this->userType;
        }

        if (null !== $this->accessLevel) {
            $attrs['accessLevel'] = $this->accessLevel;
        }

        return ['type' => 'mention', 'attrs' => $attrs];
    }
}
