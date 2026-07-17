<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Exporter\Markdown;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Exception\UnsupportedExportFormatException;
use Xen3r0\Adf\Exporter\Markdown\MarkdownExporter;
use Xen3r0\Adf\Node\Block\Document;

class MarkdownExporterTest extends TestCase
{
    private MarkdownExporter $exporter;

    protected function setUp(): void
    {
        $this->exporter = new MarkdownExporter();
    }

    /**
     * @param array<int, array<string, mixed>> $content
     */
    private function markdown(array $content): string
    {
        return $this->exporter->export(Document::load(['type' => 'doc', 'version' => 1, 'content' => $content]));
    }

    public function testParagraphAndHeading(): void
    {
        $markdown = $this->markdown([
            ['type' => 'heading', 'attrs' => ['level' => 2], 'content' => [['type' => 'text', 'text' => 'Title']]],
            ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'Hello']]],
        ]);

        $this->assertSame("## Title\n\nHello\n", $markdown);
    }

    public function testTextMarks(): void
    {
        $markdown = $this->markdown([
            ['type' => 'paragraph', 'content' => [
                ['type' => 'text', 'text' => 'bold', 'marks' => [['type' => 'strong']]],
                ['type' => 'text', 'text' => ' '],
                ['type' => 'text', 'text' => 'em', 'marks' => [['type' => 'em']]],
                ['type' => 'text', 'text' => ' '],
                ['type' => 'text', 'text' => 'code', 'marks' => [['type' => 'code']]],
                ['type' => 'text', 'text' => ' '],
                ['type' => 'text', 'text' => 'strike', 'marks' => [['type' => 'strike']]],
                ['type' => 'text', 'text' => ' '],
                ['type' => 'text', 'text' => 'bg', 'marks' => [['type' => 'backgroundColor', 'attrs' => ['color' => '#00ff00']]]],
                ['type' => 'text', 'text' => ' '],
                ['type' => 'text', 'text' => 'link', 'marks' => [['type' => 'link', 'attrs' => ['href' => 'https://example.com']]]],
            ]],
        ]);

        $this->assertSame(
            '**bold** _em_ `code` ~~strike~~ <span style="background-color:#00ff00">bg</span> [link](https://example.com)'."\n",
            $markdown
        );
    }

    public function testTextIsEscaped(): void
    {
        $markdown = $this->markdown([
            ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => '1*2 [x]']]],
        ]);

        $this->assertSame("1\\*2 \\[x\\]\n", $markdown);
    }

    public function testBulletList(): void
    {
        $markdown = $this->markdown([
            ['type' => 'bulletList', 'content' => [
                ['type' => 'listItem', 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'a']]]]],
                ['type' => 'listItem', 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'b']]]]],
            ]],
        ]);

        $this->assertSame("- a\n- b\n", $markdown);
    }

    public function testNestedBulletList(): void
    {
        $markdown = $this->markdown([
            ['type' => 'bulletList', 'content' => [
                ['type' => 'listItem', 'content' => [
                    ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'a']]],
                    ['type' => 'bulletList', 'content' => [
                        ['type' => 'listItem', 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'nested']]]]],
                    ]],
                ]],
            ]],
        ]);

        $this->assertSame("- a\n  - nested\n", $markdown);
    }

    public function testOrderedList(): void
    {
        $markdown = $this->markdown([
            ['type' => 'orderedList', 'attrs' => ['order' => 5], 'content' => [
                ['type' => 'listItem', 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'a']]]]],
                ['type' => 'listItem', 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'b']]]]],
            ]],
        ]);

        $this->assertSame("5. a\n6. b\n", $markdown);
    }

    public function testCodeBlock(): void
    {
        $markdown = $this->markdown([
            ['type' => 'codeBlock', 'attrs' => ['language' => 'php'], 'content' => [['type' => 'text', 'text' => 'echo 1;']]],
        ]);

        $this->assertSame("```php\necho 1;\n```\n", $markdown);
    }

    public function testRule(): void
    {
        $this->assertSame("---\n", $this->markdown([['type' => 'rule']]));
    }

    public function testBlockquote(): void
    {
        $markdown = $this->markdown([
            ['type' => 'blockquote', 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'quoted']]]]],
        ]);

        $this->assertSame("> quoted\n", $markdown);
    }

    public function testPanel(): void
    {
        $markdown = $this->markdown([
            ['type' => 'panel', 'attrs' => ['panelType' => 'warning'], 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'careful']]]]],
        ]);

        $this->assertSame("> **WARNING**\n> careful\n", $markdown);
    }

    public function testTableWithHeader(): void
    {
        $markdown = $this->markdown([
            ['type' => 'table', 'content' => [
                ['type' => 'tableRow', 'content' => [
                    ['type' => 'tableHeader', 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'H1']]]]],
                    ['type' => 'tableHeader', 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'H2']]]]],
                ]],
                ['type' => 'tableRow', 'content' => [
                    ['type' => 'tableCell', 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'c1']]]]],
                    ['type' => 'tableCell', 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'c2']]]]],
                ]],
            ]],
        ]);

        $this->assertSame("| H1 | H2 |\n| --- | --- |\n| c1 | c2 |\n", $markdown);
    }

    public function testTableWithoutHeader(): void
    {
        $markdown = $this->markdown([
            ['type' => 'table', 'content' => [
                ['type' => 'tableRow', 'content' => [
                    ['type' => 'tableCell', 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'c1']]]]],
                ]],
            ]],
        ]);

        $this->assertSame("|  |\n| --- |\n| c1 |\n", $markdown);
    }

    public function testEmptyTableRendersNothing(): void
    {
        $this->assertSame('', $this->markdown([['type' => 'table', 'content' => []]]));
    }

    public function testExpand(): void
    {
        $markdown = $this->markdown([
            ['type' => 'expand', 'attrs' => ['title' => 'More'], 'content' => [['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'hidden']]]]],
        ]);

        $this->assertSame("<details>\n<summary>More</summary>\n\nhidden\n\n</details>\n", $markdown);
    }

    public function testMedia(): void
    {
        $markdown = $this->markdown([
            ['type' => 'mediaGroup', 'content' => [
                ['type' => 'media', 'attrs' => ['type' => 'file', 'url' => 'https://x/y.png', 'alt' => 'y']],
            ]],
        ]);

        $this->assertSame("![y](https://x/y.png)\n", $markdown);
    }

    public function testHardBreak(): void
    {
        $markdown = $this->markdown([
            ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'a'], ['type' => 'hardBreak'], ['type' => 'text', 'text' => 'b']]],
        ]);

        $this->assertSame("a  \nb\n", $markdown);
    }

    public function testMention(): void
    {
        $markdown = $this->markdown([
            ['type' => 'paragraph', 'content' => [['type' => 'mention', 'attrs' => ['id' => 'acc-1', 'text' => '@Jane']]]],
        ]);

        $this->assertSame("@Jane\n", $markdown);
    }

    public function testEmoji(): void
    {
        $markdown = $this->markdown([
            ['type' => 'paragraph', 'content' => [['type' => 'emoji', 'attrs' => ['shortName' => ':smile:']]]],
        ]);

        $this->assertSame(":smile:\n", $markdown);
    }

    public function testDate(): void
    {
        $markdown = $this->markdown([
            ['type' => 'paragraph', 'content' => [['type' => 'date', 'attrs' => ['timestamp' => '1700000000000']]]],
        ]);

        $this->assertSame("1700000000000\n", $markdown);
    }

    public function testStatus(): void
    {
        $markdown = $this->markdown([
            ['type' => 'paragraph', 'content' => [['type' => 'status', 'attrs' => ['text' => 'Done']]]],
        ]);

        $this->assertSame("**Done**\n", $markdown);
    }

    public function testInlineCard(): void
    {
        $markdown = $this->markdown([
            ['type' => 'paragraph', 'content' => [['type' => 'inlineCard', 'attrs' => ['url' => 'https://example.com']]]],
        ]);

        $this->assertSame("[https://example.com](https://example.com)\n", $markdown);
    }

    public function testUnsupportedNodeThrows(): void
    {
        $this->expectException(UnsupportedExportFormatException::class);

        (new MarkdownExporter())->export(new class extends \Xen3r0\Adf\Node\Node {
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
