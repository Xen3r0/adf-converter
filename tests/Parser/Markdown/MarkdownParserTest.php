<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Parser\Markdown;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Block\BulletList;
use Xen3r0\Adf\Node\Block\CodeBlock;
use Xen3r0\Adf\Node\Block\Heading;
use Xen3r0\Adf\Node\Block\OrderedList;
use Xen3r0\Adf\Node\Block\Paragraph;
use Xen3r0\Adf\Node\Block\Table;
use Xen3r0\Adf\Node\Child\ListItem;
use Xen3r0\Adf\Node\Child\Media;
use Xen3r0\Adf\Node\Child\TableRow;
use Xen3r0\Adf\Node\Inline\Text;
use Xen3r0\Adf\Node\Mark\Link;
use Xen3r0\Adf\Node\Mark\Strike;
use Xen3r0\Adf\Node\Mark\Strong;
use Xen3r0\Adf\Parser\Markdown\MarkdownParser;

class MarkdownParserTest extends TestCase
{
    private MarkdownParser $parser;

    protected function setUp(): void
    {
        $this->parser = new MarkdownParser();
    }

    public function testHeadingAndParagraph(): void
    {
        $document = $this->parser->parse("## Title\n\nHello **bold**\n");

        $heading = $document->getContent()[0];
        $this->assertInstanceOf(Heading::class, $heading);
        $this->assertSame(2, $heading->getLevel());

        $paragraph = $document->getContent()[1];
        $this->assertInstanceOf(Paragraph::class, $paragraph);

        $bold = $paragraph->getContent()[1];
        $this->assertInstanceOf(Text::class, $bold);
        $this->assertSame('bold', $bold->getText());
        $this->assertInstanceOf(Strong::class, $bold->getMarks()[0]);
    }

    public function testLink(): void
    {
        $document = $this->parser->parse('[example](https://example.com)');

        $paragraph = $document->getContent()[0];
        $this->assertInstanceOf(Paragraph::class, $paragraph);

        $text = $paragraph->getContent()[0];
        $this->assertInstanceOf(Text::class, $text);
        $this->assertSame('example', $text->getText());

        $mark = $text->getMarks()[0];
        $this->assertInstanceOf(Link::class, $mark);
        $this->assertSame('https://example.com', $mark->getHref());
    }

    public function testNestedBulletList(): void
    {
        $document = $this->parser->parse("- a\n  - nested\n- b\n");

        $list = $document->getContent()[0];
        $this->assertInstanceOf(BulletList::class, $list);
        $this->assertCount(2, $list->getContent());

        $firstItem = $list->getContent()[0];
        $this->assertInstanceOf(ListItem::class, $firstItem);

        $nestedList = $firstItem->getContent()[1];
        $this->assertInstanceOf(BulletList::class, $nestedList);
    }

    public function testOrderedList(): void
    {
        $document = $this->parser->parse("1. a\n2. b\n");

        $list = $document->getContent()[0];
        $this->assertInstanceOf(OrderedList::class, $list);
        $this->assertCount(2, $list->getContent());
    }

    public function testFencedCodeBlock(): void
    {
        $document = $this->parser->parse("```php\necho 1;\n```\n");

        $codeBlock = $document->getContent()[0];
        $this->assertInstanceOf(CodeBlock::class, $codeBlock);
        $this->assertSame('php', $codeBlock->getLanguage());

        $text = $codeBlock->getContent()[0];
        $this->assertInstanceOf(Text::class, $text);
        $this->assertSame('echo 1;', $text->getText());
    }

    public function testBlockquote(): void
    {
        $document = $this->parser->parse('> quoted');

        $this->assertSame('blockquote', $document->getContent()[0]->getType());
    }

    public function testTable(): void
    {
        $document = $this->parser->parse("| H1 | H2 |\n| --- | --- |\n| c1 | c2 |\n");

        $table = $document->getContent()[0];
        $this->assertInstanceOf(Table::class, $table);
        $this->assertCount(2, $table->getContent());

        $headerRow = $table->getContent()[0];
        $this->assertInstanceOf(TableRow::class, $headerRow);
        $this->assertSame('tableHeader', $headerRow->getContent()[0]->getType());

        $dataRow = $table->getContent()[1];
        $this->assertInstanceOf(TableRow::class, $dataRow);
        $this->assertSame('tableCell', $dataRow->getContent()[0]->getType());
    }

    public function testImage(): void
    {
        $document = $this->parser->parse('![alt](https://x/y.png)');

        $paragraph = $document->getContent()[0];
        $this->assertInstanceOf(Paragraph::class, $paragraph);

        $media = $paragraph->getContent()[0];
        $this->assertInstanceOf(Media::class, $media);
        $this->assertSame('https://x/y.png', $media->getUrl());
        $this->assertSame('alt', $media->getAlt());
    }

    public function testStrikethrough(): void
    {
        $document = $this->parser->parse('~~gone~~');

        $paragraph = $document->getContent()[0];
        $this->assertInstanceOf(Paragraph::class, $paragraph);

        $text = $paragraph->getContent()[0];
        $this->assertInstanceOf(Text::class, $text);
        $this->assertSame('gone', $text->getText());
        $this->assertInstanceOf(Strike::class, $text->getMarks()[0]);
    }
}
