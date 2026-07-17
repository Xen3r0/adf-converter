<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Exporter\Html;

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

final class HtmlExporter implements ExporterInterface
{
    public function export(Node $node): string
    {
        return match (true) {
            $node instanceof Blockquote => '<blockquote>'.$this->renderChildren($node).'</blockquote>',
            $node instanceof BulletList => '<ul>'.$this->renderChildren($node).'</ul>',
            $node instanceof CodeBlock => $this->renderCodeBlock($node),
            $node instanceof Date => \sprintf('<time datetime="%1$s">%1$s</time>', htmlspecialchars($node->getTimestamp())),
            $node instanceof Document => $this->renderChildren($node),
            $node instanceof Emoji => htmlspecialchars($node->getText() ?? $node->getShortName()),
            $node instanceof Expand => $this->renderExpand($node),
            $node instanceof Hardbreak => '<br />',
            $node instanceof Heading => \sprintf('<h%1$d>%2$s</h%1$d>', $node->getLevel(), $this->renderChildren($node)),
            $node instanceof InlineCard => $this->renderInlineCard($node),
            $node instanceof ListItem => '<li>'.$this->renderChildren($node).'</li>',
            $node instanceof Media => $this->renderMedia($node),
            $node instanceof MediaGroup => '<div class="media-group">'.$this->renderChildren($node).'</div>',
            $node instanceof MediaSingle => '<div class="media-single">'.$this->renderChildren($node).'</div>',
            $node instanceof Mention => \sprintf('<span class="mention" data-id="%s">%s</span>', htmlspecialchars($node->getId()), htmlspecialchars($node->getText() ?? '@'.$node->getId())),
            $node instanceof OrderedList => $this->renderOrderedList($node),
            $node instanceof Panel => \sprintf('<div class="panel panel-%s">%s</div>', htmlspecialchars($node->getPanelType()), $this->renderChildren($node)),
            $node instanceof Paragraph => '<p>'.$this->renderChildren($node).'</p>',
            $node instanceof Rule => '<hr />',
            $node instanceof Status => \sprintf('<span class="status status-%s">%s</span>', htmlspecialchars($node->getColor() ?? 'neutral'), htmlspecialchars($node->getText())),
            $node instanceof Table => '<table><tbody>'.$this->renderChildren($node).'</tbody></table>',
            $node instanceof TableCell => $this->renderTableCell($node, 'td'),
            $node instanceof TableHeader => $this->renderTableCell($node, 'th'),
            $node instanceof TableRow => '<tr>'.$this->renderChildren($node).'</tr>',
            $node instanceof Text => $this->renderText($node),
            default => throw new UnsupportedExportFormatException($node::class),
        };
    }

    private function renderChildren(BlockNode $node): string
    {
        $html = '';

        foreach ($node->getContent() as $child) {
            $html .= $this->export($child);
        }

        return $html;
    }

    private function renderOrderedList(OrderedList $node): string
    {
        $attr = null !== $node->getOrder() ? \sprintf(' start="%d"', $node->getOrder()) : '';

        return '<ol'.$attr.'>'.$this->renderChildren($node).'</ol>';
    }

    private function renderCodeBlock(CodeBlock $node): string
    {
        $class = null !== $node->getLanguage() ? \sprintf(' class="language-%s"', htmlspecialchars($node->getLanguage())) : '';

        return '<pre><code'.$class.'>'.$this->renderChildren($node).'</code></pre>';
    }

    private function renderExpand(Expand $node): string
    {
        $summary = null !== $node->getTitle() ? '<summary>'.htmlspecialchars($node->getTitle()).'</summary>' : '';

        return '<details>'.$summary.$this->renderChildren($node).'</details>';
    }

    private function renderTableCell(TableCell|TableHeader $node, string $tag): string
    {
        $attrs = '';

        if (null !== $node->getColspan()) {
            $attrs .= \sprintf(' colspan="%d"', $node->getColspan());
        }

        if (null !== $node->getRowspan()) {
            $attrs .= \sprintf(' rowspan="%d"', $node->getRowspan());
        }

        return "<{$tag}{$attrs}>".$this->renderChildren($node)."</{$tag}>";
    }

    private function renderMedia(Media $node): string
    {
        $attrs = \sprintf(' src="%s"', htmlspecialchars((string) $node->getUrl()));

        if (null !== $node->getAlt()) {
            $attrs .= \sprintf(' alt="%s"', htmlspecialchars($node->getAlt()));
        }

        if (null !== $node->getWidth()) {
            $attrs .= \sprintf(' width="%d"', $node->getWidth());
        }

        if (null !== $node->getHeight()) {
            $attrs .= \sprintf(' height="%d"', $node->getHeight());
        }

        return '<img'.$attrs.' />';
    }

    private function renderInlineCard(InlineCard $node): string
    {
        $url = $node->getUrl() ?? (string) ($node->getData()['url'] ?? '');

        return \sprintf('<a href="%1$s">%1$s</a>', htmlspecialchars($url));
    }

    private function renderText(Text $node): string
    {
        $text = htmlspecialchars($node->getText());

        foreach ($node->getMarks() as $mark) {
            $text = $this->wrapMark($mark, $text);
        }

        return $text;
    }

    private function wrapMark(Mark $mark, string $text): string
    {
        return match (true) {
            $mark instanceof BackgroundColor => \sprintf('<span style="background-color:%s">%s</span>', htmlspecialchars($mark->getColor()), $text),
            $mark instanceof Code => "<code>{$text}</code>",
            $mark instanceof Em => "<em>{$text}</em>",
            $mark instanceof Link => $this->wrapLink($mark, $text),
            $mark instanceof Strike => "<del>{$text}</del>",
            $mark instanceof Strong => "<strong>{$text}</strong>",
            $mark instanceof Subsup => \sprintf('<%1$s>%2$s</%1$s>', $mark->getSubsupType(), $text),
            $mark instanceof TextColor => \sprintf('<span style="color:%s">%s</span>', htmlspecialchars($mark->getColor()), $text),
            $mark instanceof Underline => "<u>{$text}</u>",
            default => throw new UnsupportedExportFormatException($mark::class),
        };
    }

    private function wrapLink(Link $mark, string $text): string
    {
        $title = null !== $mark->getTitle() ? \sprintf(' title="%s"', htmlspecialchars($mark->getTitle())) : '';

        return \sprintf('<a href="%s"%s>%s</a>', htmlspecialchars($mark->getHref()), $title, $text);
    }
}
