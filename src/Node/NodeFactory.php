<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Node;

use Xen3r0\Adf\Enum\NodeType;
use Xen3r0\Adf\Exception\UnsupportedNodeTypeException;
use Xen3r0\Adf\Node\Block\Blockquote;
use Xen3r0\Adf\Node\Block\BulletList;
use Xen3r0\Adf\Node\Block\CodeBlock;
use Xen3r0\Adf\Node\Block\Document;
use Xen3r0\Adf\Node\Block\Expand;
use Xen3r0\Adf\Node\Block\Heading;
use Xen3r0\Adf\Node\Block\MediaGroup;
use Xen3r0\Adf\Node\Block\MediaSingle;
use Xen3r0\Adf\Node\Block\OrderedList;
use Xen3r0\Adf\Node\Block\Panel;
use Xen3r0\Adf\Node\Block\Paragraph;
use Xen3r0\Adf\Node\Block\Rule;
use Xen3r0\Adf\Node\Block\Table;
use Xen3r0\Adf\Node\Child\ListItem;
use Xen3r0\Adf\Node\Child\Media;
use Xen3r0\Adf\Node\Child\TableCell;
use Xen3r0\Adf\Node\Child\TableHeader;
use Xen3r0\Adf\Node\Child\TableRow;
use Xen3r0\Adf\Node\Inline\Date;
use Xen3r0\Adf\Node\Inline\Emoji;
use Xen3r0\Adf\Node\Inline\Hardbreak;
use Xen3r0\Adf\Node\Inline\InlineCard;
use Xen3r0\Adf\Node\Inline\Mention;
use Xen3r0\Adf\Node\Inline\Status;
use Xen3r0\Adf\Node\Inline\Text;

final class NodeFactory
{
    public const TYPE_MAP = [
        NodeType::Document->value => Document::class,
        NodeType::Paragraph->value => Paragraph::class,
        NodeType::Heading->value => Heading::class,
        NodeType::Blockquote->value => Blockquote::class,
        NodeType::BulletList->value => BulletList::class,
        NodeType::OrderedList->value => OrderedList::class,
        NodeType::CodeBlock->value => CodeBlock::class,
        NodeType::Rule->value => Rule::class,
        NodeType::Table->value => Table::class,
        NodeType::Panel->value => Panel::class,
        NodeType::Expand->value => Expand::class,
        NodeType::MediaGroup->value => MediaGroup::class,
        NodeType::MediaSingle->value => MediaSingle::class,
        NodeType::ListItem->value => ListItem::class,
        NodeType::TableRow->value => TableRow::class,
        NodeType::TableCell->value => TableCell::class,
        NodeType::TableHeader->value => TableHeader::class,
        NodeType::Media->value => Media::class,
        NodeType::Text->value => Text::class,
        NodeType::HardBreak->value => Hardbreak::class,
        NodeType::Mention->value => Mention::class,
        NodeType::Emoji->value => Emoji::class,
        NodeType::Date->value => Date::class,
        NodeType::Status->value => Status::class,
        NodeType::InlineCard->value => InlineCard::class,
    ];

    private function __construct()
    {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function create(array $data): Node
    {
        $type = $data['type'] ?? null;

        if (!\is_string($type) || !isset(self::TYPE_MAP[$type])) {
            throw new UnsupportedNodeTypeException(\is_string($type) ? $type : '');
        }

        $class = self::TYPE_MAP[$type];

        return $class::fromArray($data);
    }
}
