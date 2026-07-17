<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Exporter\Markdown;

use Xen3r0\Adf\Exception\UnsupportedExportFormatException;
use Xen3r0\Adf\Exporter\ExporterInterface;
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
use Xen3r0\Adf\Node\BlockNode;
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
use Xen3r0\Adf\Node\Mark\BackgroundColor;
use Xen3r0\Adf\Node\Mark\Code;
use Xen3r0\Adf\Node\Mark\Em;
use Xen3r0\Adf\Node\Mark\Link;
use Xen3r0\Adf\Node\Mark\Mark;
use Xen3r0\Adf\Node\Mark\Strike;
use Xen3r0\Adf\Node\Mark\Strong;
use Xen3r0\Adf\Node\Mark\Subsup;
use Xen3r0\Adf\Node\Mark\TextColor;
use Xen3r0\Adf\Node\Mark\Underline;
use Xen3r0\Adf\Node\Node;

final class MarkdownExporter implements ExporterInterface
{
    public function export(Node $node): string
    {
        $content = rtrim($this->renderNode($node, 0));

        return '' === $content ? '' : $content."\n";
    }

    private function renderNode(Node $node, int $depth): string
    {
        return match (true) {
            $node instanceof Document => $this->renderChildren($node, $depth),
            $node instanceof Paragraph => $this->renderInlineChildren($node)."\n\n",
            $node instanceof Heading => str_repeat('#', $node->getLevel()).' '.$this->renderInlineChildren($node)."\n\n",
            $node instanceof Blockquote => $this->renderBlockquote($node, $depth),
            $node instanceof BulletList => $this->renderList($node, $depth, false),
            $node instanceof OrderedList => $this->renderList($node, $depth, true),
            $node instanceof CodeBlock => $this->renderCodeBlock($node),
            $node instanceof Rule => "---\n\n",
            $node instanceof Table => $this->renderTable($node),
            $node instanceof Panel => $this->renderPanel($node, $depth),
            $node instanceof Expand => $this->renderExpand($node, $depth),
            $node instanceof MediaGroup => $this->renderChildren($node, $depth),
            $node instanceof MediaSingle => $this->renderChildren($node, $depth),
            $node instanceof Media => $this->renderMedia($node)."\n\n",
            default => throw new UnsupportedExportFormatException($node::class),
        };
    }

    private function renderChildren(BlockNode $node, int $depth): string
    {
        $out = '';

        foreach ($node->getContent() as $child) {
            $out .= $this->renderNode($child, $depth);
        }

        return $out;
    }

    private function renderInlineChildren(BlockNode $node): string
    {
        $out = '';

        foreach ($node->getContent() as $child) {
            $out .= $this->renderInline($child);
        }

        return $out;
    }

    private function renderInline(Node $node): string
    {
        return match (true) {
            $node instanceof Text => $this->renderText($node),
            $node instanceof Hardbreak => "  \n",
            $node instanceof Mention => $node->getText() ?? '@'.$node->getId(),
            $node instanceof Emoji => $node->getText() ?? $node->getShortName(),
            $node instanceof Date => $node->getTimestamp(),
            $node instanceof Status => '**'.$this->escape($node->getText()).'**',
            $node instanceof InlineCard => $this->renderInlineCard($node),
            $node instanceof Media => $this->renderMedia($node),
            default => throw new UnsupportedExportFormatException($node::class),
        };
    }

    private function renderInlineCard(InlineCard $node): string
    {
        $url = $node->getUrl() ?? (string) ($node->getData()['url'] ?? '');

        return \sprintf('[%1$s](%1$s)', $url);
    }

    private function renderText(Text $node): string
    {
        $text = $this->escape($node->getText());

        foreach ($node->getMarks() as $mark) {
            $text = $this->wrapMark($mark, $text);
        }

        return $text;
    }

    private function escape(string $text): string
    {
        return preg_replace('/([\\\\`*_\[\]])/', '\\\\$1', $text) ?? $text;
    }

    private function wrapMark(Mark $mark, string $text): string
    {
        return match (true) {
            $mark instanceof Strong => "**{$text}**",
            $mark instanceof Em => "_{$text}_",
            $mark instanceof Code => "`{$text}`",
            $mark instanceof Strike => "~~{$text}~~",
            $mark instanceof Underline => "<u>{$text}</u>",
            $mark instanceof Subsup => \sprintf('<%1$s>%2$s</%1$s>', $mark->getSubsupType(), $text),
            $mark instanceof TextColor => \sprintf('<span style="color:%s">%s</span>', $mark->getColor(), $text),
            $mark instanceof BackgroundColor => \sprintf('<span style="background-color:%s">%s</span>', $mark->getColor(), $text),
            $mark instanceof Link => $this->wrapLink($mark, $text),
            default => throw new UnsupportedExportFormatException($mark::class),
        };
    }

    private function wrapLink(Link $mark, string $text): string
    {
        $title = null !== $mark->getTitle() ? ' "'.$mark->getTitle().'"' : '';

        return \sprintf('[%s](%s%s)', $text, $mark->getHref(), $title);
    }

    private function renderList(BlockNode $node, int $depth, bool $ordered): string
    {
        $index = ($node instanceof OrderedList ? $node->getOrder() : null) ?? 1;
        $prefix = str_repeat('  ', $depth);
        $out = '';

        foreach ($node->getContent() as $item) {
            if (!$item instanceof ListItem) {
                continue;
            }

            $marker = $ordered ? $index.'.' : '-';
            ++$index;

            $children = $item->getContent();
            $first = array_shift($children);
            $firstText = null !== $first ? rtrim($this->renderNode($first, $depth + 1)) : '';

            $out .= $prefix.$marker.' '.$this->indentContinuation($firstText, $prefix.'  ')."\n";

            foreach ($children as $child) {
                $out .= $this->renderNode($child, $depth + 1);
            }
        }

        return $out."\n";
    }

    private function indentContinuation(string $text, string $indent): string
    {
        $lines = explode("\n", $text);
        $first = array_shift($lines);

        if ([] === $lines) {
            return $first;
        }

        return $first."\n".implode("\n", array_map(static fn (string $line): string => $indent.$line, $lines));
    }

    private function renderCodeBlock(CodeBlock $node): string
    {
        $code = '';

        foreach ($node->getContent() as $child) {
            if ($child instanceof Text) {
                $code .= $child->getText();
            }
        }

        return '```'.($node->getLanguage() ?? '')."\n{$code}\n```\n\n";
    }

    private function renderBlockquote(BlockNode $node, int $depth): string
    {
        return $this->prefixLines($this->renderChildren($node, $depth), '> ')."\n";
    }

    private function renderPanel(Panel $node, int $depth): string
    {
        $label = strtoupper($node->getPanelType());
        $content = $this->prefixLines($this->renderChildren($node, $depth), '> ');

        return "> **{$label}**\n".$content."\n";
    }

    private function renderExpand(Expand $node, int $depth): string
    {
        $summary = $node->getTitle() ?? 'Details';
        $content = rtrim($this->renderChildren($node, $depth));

        return "<details>\n<summary>{$summary}</summary>\n\n{$content}\n\n</details>\n\n";
    }

    private function prefixLines(string $content, string $prefix): string
    {
        $out = '';

        foreach (explode("\n", rtrim($content)) as $line) {
            $out .= $prefix.$line."\n";
        }

        return $out;
    }

    private function renderMedia(Media $node): string
    {
        return \sprintf('![%s](%s)', $node->getAlt() ?? '', $node->getUrl() ?? '');
    }

    private function renderTable(Table $node): string
    {
        $rows = array_values(array_filter(
            $node->getContent(),
            static fn (Node $n): bool => $n instanceof TableRow
        ));

        if ([] === $rows) {
            return '';
        }

        $cellsOf = static fn (TableRow $row): array => array_values(array_filter(
            $row->getContent(),
            static fn (Node $n): bool => $n instanceof TableCell || $n instanceof TableHeader
        ));

        $firstRowCells = $cellsOf($rows[0]);
        $hasHeader = [] !== array_filter($firstRowCells, static fn (Node $c): bool => $c instanceof TableHeader);
        $columnCount = \count($firstRowCells);

        $lines = [];
        $lines[] = '| '.implode(' | ', $hasHeader ? array_map($this->renderTableCell(...), $firstRowCells) : array_fill(0, $columnCount, '')).' |';
        $lines[] = '| '.implode(' | ', array_fill(0, $columnCount, '---')).' |';

        $dataRows = $hasHeader ? \array_slice($rows, 1) : $rows;

        foreach ($dataRows as $row) {
            $lines[] = '| '.implode(' | ', array_map($this->renderTableCell(...), $cellsOf($row))).' |';
        }

        return implode("\n", $lines)."\n\n";
    }

    private function renderTableCell(TableCell|TableHeader $cell): string
    {
        return str_replace("\n", ' ', trim($this->renderChildren($cell, 0)));
    }
}
