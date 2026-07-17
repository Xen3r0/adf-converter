<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Node\Block;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Node\Block\Document;
use Xen3r0\Adf\Node\Block\Paragraph;

class DocumentTest extends TestCase
{
    private const DATA = [
        'version' => 1,
        'type' => 'doc',
        'content' => [
            ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'Hello']]],
        ],
    ];

    public function testLoad(): void
    {
        $document = Document::load(self::DATA);

        $this->assertSame('doc', $document->getType());
        $this->assertSame(1, $document->getVersion());
        $this->assertCount(1, $document->getContent());
        $this->assertInstanceOf(Paragraph::class, $document->getContent()[0]);
    }

    public function testDefaultVersion(): void
    {
        $document = Document::fromArray(['type' => 'doc', 'content' => []]);

        $this->assertSame(1, $document->getVersion());
    }

    public function testToArrayIsFullyRecursive(): void
    {
        $document = Document::load(self::DATA);

        $this->assertSame(self::DATA, $document->toArray());
    }

    public function testFluentSetters(): void
    {
        $document = (new Document())->setVersion(2)->addContent(new Paragraph());

        $this->assertSame(2, $document->getVersion());
        $this->assertCount(1, $document->getContent());
    }

    public function testJsonEncodeIsRecursive(): void
    {
        $document = Document::load(self::DATA);

        $this->assertSame(self::DATA, json_decode((string) json_encode($document), true));
    }
}
