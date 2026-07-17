<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Exporter\Html;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Exception\UnsupportedExportFormatException;
use Xen3r0\Adf\Exporter\Html\HtmlExporter;
use Xen3r0\Adf\Node\Block\Document;

class HtmlExporterTest extends TestCase
{
    private HtmlExporter $exporter;

    protected function setUp(): void
    {
        $this->exporter = new HtmlExporter();
    }

    /**
     * @param array<int, array<string, mixed>> $content
     */
    private function html(array $content): string
    {
        return $this->exporter->export(Document::load(['type' => 'doc', 'version' => 1, 'content' => $content]));
    }

    public function testParagraphAndHeading(): void
    {
        $html = $this->html([
            ['type' => 'heading', 'attrs' => ['level' => 2], 'content' => [['type' => 'text', 'text' => 'Title']]],
            ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'Hello']]],
        ]);

        $this->assertSame('<h2>Title</h2><p>Hello</p>', $html);
    }

    public function testTextMarks(): void
    {
        $html = $this->html([
            ['type' => 'paragraph', 'content' => [
                ['type' => 'text', 'text' => 'bold', 'marks' => [['type' => 'strong']]],
                ['type' => 'text', 'text' => 'em', 'marks' => [['type' => 'em']]],
                ['type' => 'text', 'text' => 'code', 'marks' => [['type' => 'code']]],
                ['type' => 'text', 'text' => 'strike', 'marks' => [['type' => 'strike']]],
                ['type' => 'text', 'text' => 'under', 'marks' => [['type' => 'underline']]],
                ['type' => 'text', 'text' => 'sup', 'marks' => [['type' => 'subsup', 'attrs' => ['type' => 'sup']]]],
                ['type' => 'text', 'text' => 'color', 'marks' => [['type' => 'textColor', 'attrs' => ['color' => '#ff0000']]]],
                ['type' => 'text', 'text' => 'bg', 'marks' => [['type' => 'backgroundColor', 'attrs' => ['color' => '#00ff00']]]],
                ['type' => 'text', 'text' => 'link', 'marks' => [['type' => 'link', 'attrs' => ['href' => 'https://example.com', 'title' => 'Ex']]]],
            ]],
        ]);

        $this->assertSame(
            '<p><strong>bold</strong><em>em</em><code>code</code><del>strike</del><u>under</u>'
            .'<sup>sup</sup><span style="color:#ff0000">color</span>'
            .'<span style="background-color:#00ff00">bg</span>'
            .'<a href="https://example.com" title="Ex">link</a></p>',
            $html
        );
    }

    public function testTextIsEscaped(): void
    {
        $html = $this->html([
            ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => '<script>&"\'']]],
        ]);

        $this->assertSame('<p>&lt;script&gt;&amp;&quot;&#039;</p>', $html);
    }

    public function testBlockquote(): void
    {
        $html = $this->html([
            ['type' => 'blockquote', 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'quote']]]]],
        ]);

        $this->assertSame('<blockquote><p>quote</p></blockquote>', $html);
    }

    public function testBulletAndOrderedList(): void
    {
        $html = $this->html([
            ['type' => 'bulletList', 'content' => [
                ['type' => 'listItem', 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'a']]]]],
            ]],
            ['type' => 'orderedList', 'attrs' => ['order' => 3], 'content' => [
                ['type' => 'listItem', 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'b']]]]],
            ]],
        ]);

        $this->assertSame('<ul><li><p>a</p></li></ul><ol start="3"><li><p>b</p></li></ol>', $html);
    }

    public function testCodeBlock(): void
    {
        $html = $this->html([
            ['type' => 'codeBlock', 'attrs' => ['language' => 'php'], 'content' => [['type' => 'text', 'text' => 'echo 1;']]],
        ]);

        $this->assertSame('<pre><code class="language-php">echo 1;</code></pre>', $html);
    }

    public function testRule(): void
    {
        $this->assertSame('<hr />', $this->html([['type' => 'rule']]));
    }

    public function testPanel(): void
    {
        $html = $this->html([
            ['type' => 'panel', 'attrs' => ['panelType' => 'warning'], 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'careful']]]]],
        ]);

        $this->assertSame('<div class="panel panel-warning"><p>careful</p></div>', $html);
    }

    public function testExpand(): void
    {
        $html = $this->html([
            ['type' => 'expand', 'attrs' => ['title' => 'More'], 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'hidden']]]]],
        ]);

        $this->assertSame('<details><summary>More</summary><p>hidden</p></details>', $html);
    }

    public function testTableWithHeaderAndCells(): void
    {
        $html = $this->html([
            ['type' => 'table', 'content' => [
                ['type' => 'tableRow', 'content' => [
                    ['type' => 'tableHeader', 'attrs' => ['colspan' => 2], 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'H']]]]],
                ]],
                ['type' => 'tableRow', 'content' => [
                    ['type' => 'tableCell', 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'c']]]]],
                ]],
            ]],
        ]);

        $this->assertSame(
            '<table><tbody><tr><th colspan="2"><p>H</p></th></tr><tr><td><p>c</p></td></tr></tbody></table>',
            $html
        );
    }

    public function testMediaGroupAndMedia(): void
    {
        $html = $this->html([
            ['type' => 'mediaGroup', 'content' => [
                ['type' => 'media', 'attrs' => ['type' => 'file', 'url' => 'https://x/y.png', 'alt' => 'y', 'width' => 10, 'height' => 20]],
            ]],
        ]);

        $this->assertSame(
            '<div class="media-group"><img src="https://x/y.png" alt="y" width="10" height="20" /></div>',
            $html
        );
    }

    public function testHardBreak(): void
    {
        $html = $this->html([
            ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'a'], ['type' => 'hardBreak'], ['type' => 'text', 'text' => 'b']]],
        ]);

        $this->assertSame('<p>a<br />b</p>', $html);
    }

    public function testMention(): void
    {
        $html = $this->html([
            ['type' => 'paragraph', 'content' => [['type' => 'mention', 'attrs' => ['id' => 'acc-1', 'text' => '@Jane']]]],
        ]);

        $this->assertSame('<p><span class="mention" data-id="acc-1">@Jane</span></p>', $html);
    }

    public function testEmoji(): void
    {
        $html = $this->html([
            ['type' => 'paragraph', 'content' => [['type' => 'emoji', 'attrs' => ['shortName' => ':smile:', 'text' => '😄']]]],
        ]);

        $this->assertSame('<p>😄</p>', $html);
    }

    public function testDate(): void
    {
        $html = $this->html([
            ['type' => 'paragraph', 'content' => [['type' => 'date', 'attrs' => ['timestamp' => '1700000000000']]]],
        ]);

        $this->assertSame('<p><time datetime="1700000000000">1700000000000</time></p>', $html);
    }

    public function testStatus(): void
    {
        $html = $this->html([
            ['type' => 'paragraph', 'content' => [['type' => 'status', 'attrs' => ['text' => 'Done', 'color' => 'green']]]],
        ]);

        $this->assertSame('<p><span class="status status-green">Done</span></p>', $html);
    }

    public function testInlineCard(): void
    {
        $html = $this->html([
            ['type' => 'paragraph', 'content' => [['type' => 'inlineCard', 'attrs' => ['url' => 'https://example.com']]]],
        ]);

        $this->assertSame('<p><a href="https://example.com">https://example.com</a></p>', $html);
    }

    public function testUnsupportedNodeThrows(): void
    {
        $this->expectException(UnsupportedExportFormatException::class);

        (new HtmlExporter())->export(new class extends \Xen3r0\Adf\Node\Node {
            public function getType(): string
            {
                return 'custom';
            }

            /**
             * @param array<string, mixed> $data
             */
            public static function fromArray(array $data): static
            {
                return new self();
            }

            /**
             * @return array<string, mixed>
             */
            public function jsonSerialize(): array
            {
                return ['type' => 'custom'];
            }
        });
    }
}
