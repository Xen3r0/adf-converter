<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Mark;

use Xen3r0\Adf\Enum\MarkType;

final class Link extends Mark
{
    private string $href = '';
    private ?string $title = null;

    public function getType(): string
    {
        return MarkType::Link->value;
    }

    public function getHref(): string
    {
        return $this->href;
    }

    public function setHref(string $href): static
    {
        $this->href = $href;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    protected function attrs(): array
    {
        $attrs = ['href' => $this->href];

        if (null !== $this->title) {
            $attrs['title'] = $this->title;
        }

        return $attrs;
    }

    public static function fromArray(array $data): static
    {
        $mark = (new self())->setHref((string) ($data['attrs']['href'] ?? ''));

        if (isset($data['attrs']['title'])) {
            $mark->setTitle((string) $data['attrs']['title']);
        }

        return $mark;
    }
}
