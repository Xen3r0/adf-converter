<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Parser\Html;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Block\BulletList;
use Xen3r0\Adf\Node\Block\Document;
use Xen3r0\Adf\Node\Block\MediaGroup;
use Xen3r0\Adf\Node\Block\OrderedList;
use Xen3r0\Adf\Node\Block\Table;
use Xen3r0\Adf\Node\Child\ListItem;
use Xen3r0\Adf\Node\Child\Media;
use Xen3r0\Adf\Node\Child\TableCell;
use Xen3r0\Adf\Node\Child\TableRow;
use Xen3r0\Adf\Parser\Html\HtmlParser;

class HtmlParserTest extends TestCase
{
    private HtmlParser $parser;

    protected function setUp(): void
    {
        $this->parser = new HtmlParser();
    }

    private function parse(string $html): Document
    {
        return $this->parser->parse($html);
    }

    public function testHeadingAndParagraph(): void
    {
        $document = $this->parse('<h2>Title</h2><p>Hello</p>');

        $this->assertSame(
            [
                ['type' => 'heading', 'attrs' => ['level' => 2], 'content' => [['type' => 'text', 'text' => 'Title']]],
                ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'Hello']]],
            ],
            $document->toArray()['content']
        );
    }

    public function testTextMarks(): void
    {
        $document = $this->parse(
            '<p><strong>bold</strong><em>em</em><code>code</code><del>strike</del><u>under</u>'
            .'<sup>sup</sup><span style="color:#ff0000">color</span>'
            .'<span style="background-color:#00ff00">bg</span>'
            .'<a href="https://example.com" title="Ex">link</a></p>'
        );

        $this->assertSame(
            [[
                'type' => 'paragraph',
                'content' => [
                    ['type' => 'text', 'text' => 'bold', 'marks' => [['type' => 'strong']]],
                    ['type' => 'text', 'text' => 'em', 'marks' => [['type' => 'em']]],
                    ['type' => 'text', 'text' => 'code', 'marks' => [['type' => 'code']]],
                    ['type' => 'text', 'text' => 'strike', 'marks' => [['type' => 'strike']]],
                    ['type' => 'text', 'text' => 'under', 'marks' => [['type' => 'underline']]],
                    ['type' => 'text', 'text' => 'sup', 'marks' => [['type' => 'subsup', 'attrs' => ['type' => 'sup']]]],
                    ['type' => 'text', 'text' => 'color', 'marks' => [['type' => 'textColor', 'attrs' => ['color' => '#ff0000']]]],
                    ['type' => 'text', 'text' => 'bg', 'marks' => [['type' => 'backgroundColor', 'attrs' => ['color' => '#00ff00']]]],
                    ['type' => 'text', 'text' => 'link', 'marks' => [['type' => 'link', 'attrs' => ['href' => 'https://example.com', 'title' => 'Ex']]]],
                ],
            ]],
            $document->toArray()['content']
        );
    }

    public function testCombinedColorAndBackgroundColorOnSameSpan(): void
    {
        $document = $this->parse('<p><span style="color:#ff0000;background-color:#00ff00">both</span></p>');

        $this->assertSame(
            [[
                'type' => 'paragraph',
                'content' => [
                    [
                        'type' => 'text',
                        'text' => 'both',
                        'marks' => [
                            ['type' => 'backgroundColor', 'attrs' => ['color' => '#00ff00']],
                            ['type' => 'textColor', 'attrs' => ['color' => '#ff0000']],
                        ],
                    ],
                ],
            ]],
            $document->toArray()['content']
        );
    }

    public function testBoldAndItalicShorthandTags(): void
    {
        $document = $this->parse('<p><b>bold</b><i>italic</i></p>');

        $this->assertSame(
            [[
                'type' => 'paragraph',
                'content' => [
                    ['type' => 'text', 'text' => 'bold', 'marks' => [['type' => 'strong']]],
                    ['type' => 'text', 'text' => 'italic', 'marks' => [['type' => 'em']]],
                ],
            ]],
            $document->toArray()['content']
        );
    }

    public function testBlockquote(): void
    {
        $document = $this->parse('<blockquote><p>quote</p></blockquote>');

        $this->assertSame(
            [['type' => 'blockquote', 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'quote']]]]]],
            $document->toArray()['content']
        );
    }

    public function testNestedBulletList(): void
    {
        $document = $this->parse('<ul><li><p>a</p><ul><li><p>nested</p></li></ul></li><li><p>b</p></li></ul>');

        $content = $document->toArray()['content'];
        $this->assertSame('bulletList', $content[0]['type']);
        $this->assertCount(2, $content[0]['content']);
        $this->assertSame(
            [
                ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'a']]],
                ['type' => 'bulletList', 'content' => [
                    ['type' => 'listItem', 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'nested']]]]],
                ]],
            ],
            $content[0]['content'][0]['content']
        );
    }

    public function testBareListItemWithoutParagraphWrapper(): void
    {
        $document = $this->parse('<ul><li>plain</li></ul>');

        $this->assertSame(
            [['type' => 'bulletList', 'content' => [
                ['type' => 'listItem', 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'plain']]]]],
            ]]],
            $document->toArray()['content']
        );
    }

    public function testOrderedListWithStart(): void
    {
        $document = $this->parse('<ol start="3"><li><p>a</p></li></ol>');

        $list = $document->getContent()[0];
        $this->assertInstanceOf(OrderedList::class, $list);
        $this->assertSame(3, $list->getOrder());
    }

    public function testCodeBlockWithLanguage(): void
    {
        $document = $this->parse('<pre><code class="language-php">echo 1;</code></pre>');

        $this->assertSame(
            [['type' => 'codeBlock', 'attrs' => ['language' => 'php'], 'content' => [['type' => 'text', 'text' => 'echo 1;']]]],
            $document->toArray()['content']
        );
    }

    public function testCodeBlockWithoutLanguage(): void
    {
        $document = $this->parse('<pre><code>echo 1;</code></pre>');

        $this->assertSame(
            [['type' => 'codeBlock', 'content' => [['type' => 'text', 'text' => 'echo 1;']]]],
            $document->toArray()['content']
        );
    }

    public function testRule(): void
    {
        $document = $this->parse('<hr />');

        $this->assertSame([['type' => 'rule']], $document->toArray()['content']);
    }

    public function testPanel(): void
    {
        $document = $this->parse('<div class="panel panel-warning"><p>careful</p></div>');

        $this->assertSame(
            [['type' => 'panel', 'attrs' => ['panelType' => 'warning'], 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'careful']]]]]],
            $document->toArray()['content']
        );
    }

    public function testExpand(): void
    {
        $document = $this->parse('<details><summary>More</summary><p>hidden</p></details>');

        $this->assertSame(
            [['type' => 'expand', 'attrs' => ['title' => 'More'], 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'hidden']]]]]],
            $document->toArray()['content']
        );
    }

    public function testTableWithHeaderAndCellsWithoutParagraphWrapper(): void
    {
        $document = $this->parse('<table><tbody><tr><th colspan="2">H</th></tr><tr><td>c</td></tr></tbody></table>');

        $this->assertSame(
            [['type' => 'table', 'content' => [
                ['type' => 'tableRow', 'content' => [
                    ['type' => 'tableHeader', 'attrs' => ['colspan' => 2], 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'H']]]]],
                ]],
                ['type' => 'tableRow', 'content' => [
                    ['type' => 'tableCell', 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'c']]]]],
                ]],
            ]]],
            $document->toArray()['content']
        );
    }

    public function testMediaGroupAndMedia(): void
    {
        $document = $this->parse('<div class="media-group"><img src="https://x/y.png" alt="y" width="10" height="20" /></div>');

        $mediaGroup = $document->getContent()[0];
        $this->assertInstanceOf(MediaGroup::class, $mediaGroup);

        $media = $mediaGroup->getContent()[0];
        $this->assertInstanceOf(Media::class, $media);
        $this->assertSame('https://x/y.png', $media->getUrl());
        $this->assertSame('y', $media->getAlt());
        $this->assertSame(10, $media->getWidth());
        $this->assertSame(20, $media->getHeight());
    }

    public function testStandaloneImageBecomesMediaSingle(): void
    {
        $document = $this->parse('<img src="https://x/y.png" alt="y" />');

        $this->assertSame('mediaSingle', $document->toArray()['content'][0]['type']);
    }

    public function testHardBreak(): void
    {
        $document = $this->parse('<p>a<br />b</p>');

        $this->assertSame(
            [['type' => 'paragraph', 'content' => [
                ['type' => 'text', 'text' => 'a'],
                ['type' => 'hardBreak'],
                ['type' => 'text', 'text' => 'b'],
            ]]],
            $document->toArray()['content']
        );
    }

    public function testMention(): void
    {
        $document = $this->parse('<p><span class="mention" data-id="acc-1">@Jane</span></p>');

        $this->assertSame(
            [['type' => 'paragraph', 'content' => [
                ['type' => 'mention', 'attrs' => ['id' => 'acc-1', 'text' => '@Jane']],
            ]]],
            $document->toArray()['content']
        );
    }

    public function testStatus(): void
    {
        $document = $this->parse('<p><span class="status status-green">Done</span></p>');

        $this->assertSame(
            [['type' => 'paragraph', 'content' => [
                ['type' => 'status', 'attrs' => ['text' => 'Done', 'color' => 'green']],
            ]]],
            $document->toArray()['content']
        );
    }

    public function testDate(): void
    {
        $document = $this->parse('<p><time datetime="1700000000000">1700000000000</time></p>');

        $this->assertSame(
            [['type' => 'paragraph', 'content' => [
                ['type' => 'date', 'attrs' => ['timestamp' => '1700000000000']],
            ]]],
            $document->toArray()['content']
        );
    }

    public function testInlineCardWhenLinkTextEqualsHref(): void
    {
        $document = $this->parse('<p><a href="https://example.com">https://example.com</a></p>');

        $this->assertSame(
            [['type' => 'paragraph', 'content' => [
                ['type' => 'inlineCard', 'attrs' => ['url' => 'https://example.com']],
            ]]],
            $document->toArray()['content']
        );
    }

    public function testGenericWrapperElementsAreUnwrapped(): void
    {
        $document = $this->parse('<section><p>Hello</p></section>');

        $this->assertSame(
            [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'Hello']]]],
            $document->toArray()['content']
        );
    }

    public function testEmptyContentProducesEmptyDocument(): void
    {
        $document = $this->parse('');

        $this->assertSame([], $document->getContent());
    }

    public function testHtmlCommentsAreIgnored(): void
    {
        $document = $this->parse('<!-- a comment --><p>Hello <!-- inline --> world</p>');

        $this->assertSame(
            [['type' => 'paragraph', 'content' => [
                ['type' => 'text', 'text' => 'Hello '],
                ['type' => 'text', 'text' => ' world'],
            ]]],
            $document->toArray()['content']
        );
    }

    public function testNestedOrderedListInsideListItem(): void
    {
        $document = $this->parse('<ul><li><p>a</p><ol><li><p>nested</p></li></ol></li></ul>');

        $list = $document->getContent()[0];
        $this->assertInstanceOf(BulletList::class, $list);

        $firstItem = $list->getContent()[0];
        $this->assertInstanceOf(ListItem::class, $firstItem);

        $this->assertSame('orderedList', $firstItem->getContent()[1]->getType());
    }

    public function testTableCellRowspan(): void
    {
        $document = $this->parse('<table><tbody><tr><td rowspan="2">c</td></tr></tbody></table>');

        $table = $document->getContent()[0];
        $this->assertInstanceOf(Table::class, $table);

        $row = $table->getContent()[0];
        $this->assertInstanceOf(TableRow::class, $row);

        $cell = $row->getContent()[0];
        $this->assertInstanceOf(TableCell::class, $cell);
        $this->assertSame(2, $cell->getRowspan());
    }

    public function testSubscriptMark(): void
    {
        $document = $this->parse('<p><sub>sub</sub></p>');

        $this->assertSame(
            [['type' => 'paragraph', 'content' => [
                ['type' => 'text', 'text' => 'sub', 'marks' => [['type' => 'subsup', 'attrs' => ['type' => 'sub']]]],
            ]]],
            $document->toArray()['content']
        );
    }

    public function testGenericSpanIsUnwrapped(): void
    {
        $document = $this->parse('<p><span>plain</span></p>');

        $this->assertSame(
            [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'plain']]]],
            $document->toArray()['content']
        );
    }

    public function testMediaSingleDivWrapper(): void
    {
        $document = $this->parse('<div class="media-single"><img src="https://x/y.png" /></div>');

        $this->assertSame('mediaSingle', $document->toArray()['content'][0]['type']);
    }

    public function testGenericDivIsUnwrapped(): void
    {
        $document = $this->parse('<div><p>Hello</p></div>');

        $this->assertSame(
            [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'Hello']]]],
            $document->toArray()['content']
        );
    }
}
