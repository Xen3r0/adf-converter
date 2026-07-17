<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Parser\Html;

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
use Xen3r0\Adf\Parser\ParserInterface;

final class HtmlParser implements ParserInterface
{
    private const BLOCK_LIST_TAGS = ['ul', 'ol'];
    private const TABLE_CELL_TAGS = ['td', 'th'];

    public function parse(string $content): Document
    {
        $dom = new \DOMDocument();
        $previousErrorSetting = libxml_use_internal_errors(true);

        $dom->loadHTML(
            '<?xml encoding="utf-8"?><div id="__adf_root__">'.$content.'</div>',
            \LIBXML_NOERROR | \LIBXML_NOWARNING | \LIBXML_HTML_NOIMPLIED | \LIBXML_HTML_NODEFDTD
        );

        libxml_use_internal_errors($previousErrorSetting);

        $root = $dom->getElementById('__adf_root__');
        $document = new Document();

        if (null === $root) {
            return $document;
        }

        foreach ($root->childNodes as $child) {
            foreach ($this->parseBlock($child) as $node) {
                $document->addContent($node);
            }
        }

        return $document;
    }

    /**
     * @return Node[]
     */
    private function parseBlock(\DOMNode $node): array
    {
        if ($node instanceof \DOMText) {
            $text = trim($node->textContent);

            return '' === $text ? [] : [(new Paragraph())->addContent(new Text($text))];
        }

        if (!$node instanceof \DOMElement) {
            return [];
        }

        return match (strtolower($node->tagName)) {
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6' => [
                (new Heading())
                    ->setLevel((int) substr($node->tagName, 1))
                    ->setContent($this->parseInlineChildren($node, [])),
            ],
            'p' => [(new Paragraph())->setContent($this->parseInlineChildren($node, []))],
            'blockquote' => [(new Blockquote())->setContent($this->parseChildBlocks($node))],
            'ul' => [(new BulletList())->setContent($this->parseListItems($node))],
            'ol' => [$this->parseOrderedList($node)],
            'pre' => [$this->parseCodeBlock($node)],
            'hr' => [new Rule()],
            'table' => [$this->parseTable($node)],
            'details' => [$this->parseExpand($node)],
            'img' => [(new MediaSingle())->addContent($this->parseMedia($node))],
            'div' => $this->parseDiv($node),
            default => $this->parseChildBlocks($node),
        };
    }

    /**
     * @return Node[]
     */
    private function parseChildBlocks(\DOMNode $node): array
    {
        $blocks = [];

        foreach ($node->childNodes as $child) {
            $blocks = [...$blocks, ...$this->parseBlock($child)];
        }

        return $blocks;
    }

    /**
     * @return Node[]
     */
    private function parseDiv(\DOMElement $node): array
    {
        $class = $node->getAttribute('class');

        if (preg_match('/(?:^|\s)panel-(\S+)/', $class, $matches)) {
            return [(new Panel())->setPanelType($matches[1])->setContent($this->parseChildBlocks($node))];
        }

        if (str_contains($class, 'media-group')) {
            return [(new MediaGroup())->setContent($this->parseMediaChildren($node))];
        }

        if (str_contains($class, 'media-single')) {
            return [(new MediaSingle())->setContent($this->parseMediaChildren($node))];
        }

        return $this->parseChildBlocks($node);
    }

    /**
     * @return Media[]
     */
    private function parseMediaChildren(\DOMElement $node): array
    {
        $medias = [];

        foreach ($node->childNodes as $child) {
            if ($child instanceof \DOMElement && 'img' === strtolower($child->tagName)) {
                $medias[] = $this->parseMedia($child);
            }
        }

        return $medias;
    }

    /**
     * @return ListItem[]
     */
    private function parseListItems(\DOMElement $node): array
    {
        $items = [];

        foreach ($node->childNodes as $child) {
            if ($child instanceof \DOMElement && 'li' === strtolower($child->tagName)) {
                $items[] = (new ListItem())->setContent($this->parseListItemContent($child));
            }
        }

        return $items;
    }

    /**
     * @return Node[]
     */
    private function parseListItemContent(\DOMElement $li): array
    {
        $blocks = [];
        $inlineBuffer = [];

        foreach ($li->childNodes as $child) {
            $tag = $child instanceof \DOMElement ? strtolower($child->tagName) : null;

            if (\in_array($tag, self::BLOCK_LIST_TAGS, true)) {
                $blocks = $this->flushInlineBuffer($blocks, $inlineBuffer);
                $inlineBuffer = [];
                $blocks[] = 'ul' === $tag
                    ? (new BulletList())->setContent($this->parseListItems($child))
                    : $this->parseOrderedList($child);

                continue;
            }

            if ('p' === $tag) {
                $blocks = $this->flushInlineBuffer($blocks, $inlineBuffer);
                $inlineBuffer = [];
                $blocks[] = (new Paragraph())->setContent($this->parseInlineChildren($child, []));

                continue;
            }

            if ($child instanceof \DOMText && $this->isInsignificantWhitespace($child->textContent)) {
                continue;
            }

            $inlineBuffer = [...$inlineBuffer, ...$this->parseInline($child, [])];
        }

        return $this->flushInlineBuffer($blocks, $inlineBuffer);
    }

    /**
     * Whitespace-only text nodes containing a newline are pretty-printing
     * padding emitted by HTML renderers (e.g. CommonMark) between block-level
     * siblings, not meaningful content. A plain space-only text node can still
     * be a deliberate separator between two inline elements, so only the
     * newline-bearing form is dropped.
     */
    private function isInsignificantWhitespace(string $text): bool
    {
        return '' === trim($text) && str_contains($text, "\n");
    }

    /**
     * @param Node[] $blocks
     * @param Node[] $inlineBuffer
     *
     * @return Node[]
     */
    private function flushInlineBuffer(array $blocks, array $inlineBuffer): array
    {
        if ([] === $inlineBuffer) {
            return $blocks;
        }

        $blocks[] = (new Paragraph())->setContent($inlineBuffer);

        return $blocks;
    }

    private function parseOrderedList(\DOMElement $node): OrderedList
    {
        $list = (new OrderedList())->setContent($this->parseListItems($node));

        if ($node->hasAttribute('start')) {
            $list->setOrder((int) $node->getAttribute('start'));
        }

        return $list;
    }

    private function parseCodeBlock(\DOMElement $pre): CodeBlock
    {
        $codeElement = null;

        foreach ($pre->childNodes as $child) {
            if ($child instanceof \DOMElement && 'code' === strtolower($child->tagName)) {
                $codeElement = $child;

                break;
            }
        }

        $text = null !== $codeElement ? $codeElement->textContent : $pre->textContent;
        $codeBlock = (new CodeBlock())->addContent(new Text(rtrim($text, "\n")));

        $class = null !== $codeElement ? $codeElement->getAttribute('class') : '';
        if (preg_match('/language-(\S+)/', $class, $matches)) {
            $codeBlock->setLanguage($matches[1]);
        }

        return $codeBlock;
    }

    private function parseTable(\DOMElement $node): Table
    {
        $rows = [];

        foreach ($node->getElementsByTagName('tr') as $tr) {
            $cells = [];

            foreach ($tr->childNodes as $cellNode) {
                if (!$cellNode instanceof \DOMElement) {
                    continue;
                }

                $tag = strtolower($cellNode->tagName);
                if (!\in_array($tag, self::TABLE_CELL_TAGS, true)) {
                    continue;
                }

                $cell = 'th' === $tag ? new TableHeader() : new TableCell();
                $cell->setContent($this->parseChildBlocks($cellNode));

                if ($cellNode->hasAttribute('colspan')) {
                    $cell->setColspan((int) $cellNode->getAttribute('colspan'));
                }

                if ($cellNode->hasAttribute('rowspan')) {
                    $cell->setRowspan((int) $cellNode->getAttribute('rowspan'));
                }

                $cells[] = $cell;
            }

            $rows[] = (new TableRow())->setContent($cells);
        }

        return (new Table())->setContent($rows);
    }

    private function parseExpand(\DOMElement $node): Expand
    {
        $expand = new Expand();
        $blocks = [];

        foreach ($node->childNodes as $child) {
            if ($child instanceof \DOMElement && 'summary' === strtolower($child->tagName)) {
                $expand->setTitle(trim($child->textContent));

                continue;
            }

            $blocks = [...$blocks, ...$this->parseBlock($child)];
        }

        return $expand->setContent($blocks);
    }

    private function parseMedia(\DOMElement $node): Media
    {
        $media = (new Media())->setMediaType('file')->setUrl($node->getAttribute('src'));

        if ($node->hasAttribute('alt')) {
            $media->setAlt($node->getAttribute('alt'));
        }

        if ($node->hasAttribute('width')) {
            $media->setWidth((int) $node->getAttribute('width'));
        }

        if ($node->hasAttribute('height')) {
            $media->setHeight((int) $node->getAttribute('height'));
        }

        return $media;
    }

    /**
     * @param Mark[] $marks
     *
     * @return Node[]
     */
    private function parseInlineChildren(\DOMNode $node, array $marks): array
    {
        $inline = [];

        foreach ($node->childNodes as $child) {
            $inline = [...$inline, ...$this->parseInline($child, $marks)];
        }

        return $inline;
    }

    /**
     * @param Mark[] $marks
     *
     * @return Node[]
     */
    private function parseInline(\DOMNode $node, array $marks): array
    {
        if ($node instanceof \DOMText) {
            $text = $node->textContent;

            return '' === $text ? [] : [(new Text($text))->setMarks($marks)];
        }

        if (!$node instanceof \DOMElement) {
            return [];
        }

        return match (strtolower($node->tagName)) {
            'strong', 'b' => $this->parseInlineChildren($node, [...$marks, new Strong()]),
            'em', 'i' => $this->parseInlineChildren($node, [...$marks, new Em()]),
            'code' => $this->parseInlineChildren($node, [...$marks, new Code()]),
            'del', 's', 'strike' => $this->parseInlineChildren($node, [...$marks, new Strike()]),
            'u' => $this->parseInlineChildren($node, [...$marks, new Underline()]),
            'sup' => $this->parseInlineChildren($node, [...$marks, (new Subsup())->setSubsupType('sup')]),
            'sub' => $this->parseInlineChildren($node, [...$marks, (new Subsup())->setSubsupType('sub')]),
            'span' => $this->parseSpan($node, $marks),
            'a' => $this->parseAnchor($node, $marks),
            'br' => [new Hardbreak()],
            'time' => [(new Date())->setTimestamp($node->getAttribute('datetime'))],
            'img' => [$this->parseMedia($node)],
            default => $this->parseInlineChildren($node, $marks),
        };
    }

    /**
     * @param Mark[] $marks
     *
     * @return Node[]
     */
    private function parseSpan(\DOMElement $node, array $marks): array
    {
        $class = $node->getAttribute('class');

        if (str_contains($class, 'mention')) {
            return [(new Mention())->setId($node->getAttribute('data-id'))->setText($node->textContent)];
        }

        if (preg_match('/(?:^|\s)status(?:\s|$)/', $class)) {
            $status = (new Status())->setText($node->textContent);

            if (preg_match('/status-(\S+)/', $class, $matches)) {
                $status->setColor($matches[1]);
            }

            return [$status];
        }

        $style = $node->getAttribute('style');

        if (preg_match('/background-color\s*:\s*([^;]+)/i', $style, $matches)) {
            $marks = [...$marks, (new BackgroundColor())->setColor(trim($matches[1]))];
        }

        if (preg_match('/(?<!-)color\s*:\s*([^;]+)/i', $style, $matches)) {
            $marks = [...$marks, (new TextColor())->setColor(trim($matches[1]))];
        }

        return $this->parseInlineChildren($node, $marks);
    }

    /**
     * @param Mark[] $marks
     *
     * @return Node[]
     */
    private function parseAnchor(\DOMElement $node, array $marks): array
    {
        $href = $node->getAttribute('href');

        if ($node->textContent === $href) {
            return [(new InlineCard())->setUrl($href)];
        }

        $link = (new Link())->setHref($href);

        if ($node->hasAttribute('title')) {
            $link->setTitle($node->getAttribute('title'));
        }

        return $this->parseInlineChildren($node, [...$marks, $link]);
    }
}
