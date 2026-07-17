<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Enum;

/**
 * @codeCoverageIgnore
 */
enum NodeType: string
{
    case Blockquote = 'blockquote';
    case BulletList = 'bulletList';
    case CodeBlock = 'codeBlock';
    case Date = 'date';
    case Document = 'doc';
    case Emoji = 'emoji';
    case Expand = 'expand';
    case HardBreak = 'hardBreak';
    case Heading = 'heading';
    case InlineCard = 'inlineCard';
    case ListItem = 'listItem';
    case Media = 'media';
    case MediaGroup = 'mediaGroup';
    case MediaSingle = 'mediaSingle';
    case Mention = 'mention';
    case OrderedList = 'orderedList';
    case Panel = 'panel';
    case Paragraph = 'paragraph';
    case Rule = 'rule';
    case Status = 'status';
    case Table = 'table';
    case TableCell = 'tableCell';
    case TableHeader = 'tableHeader';
    case TableRow = 'tableRow';
    case Text = 'text';
}
