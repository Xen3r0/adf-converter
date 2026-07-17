<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node\Inline;

use Xen3r0\Adf\Enum\NodeType;
use Xen3r0\Adf\Node\InlineNode;
use Xen3r0\Adf\Node\Mark\Mark;
use Xen3r0\Adf\Node\Mark\MarkFactory;

final class Text extends InlineNode
{
    private string $text;

    /** @var Mark[] */
    private array $marks = [];

    public function __construct(string $text = '')
    {
        $this->text = $text;
    }

    public function getType(): string
    {
        return NodeType::Text->value;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return Mark[]
     */
    public function getMarks(): array
    {
        return $this->marks;
    }

    /**
     * @param Mark[] $marks
     */
    public function setMarks(array $marks): static
    {
        $this->marks = $marks;

        return $this;
    }

    public function addMark(Mark $mark): static
    {
        $this->marks[] = $mark;

        return $this;
    }

    public function hasMark(string $markClass): bool
    {
        foreach ($this->marks as $mark) {
            if ($mark instanceof $markClass) {
                return true;
            }
        }

        return false;
    }

    public static function fromArray(array $data): static
    {
        return (new self((string) ($data['text'] ?? '')))->setMarks(MarkFactory::createAll($data));
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $data = ['type' => 'text', 'text' => $this->text];

        if ([] !== $this->marks) {
            $data['marks'] = $this->marks;
        }

        return $data;
    }
}
