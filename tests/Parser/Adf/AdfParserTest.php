<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Tests\Parser\Adf;

use PHPUnit\Framework\TestCase;
use Xen3r0\Adf\Exception\InvalidAdfDocumentException;
use Xen3r0\Adf\Node\Block\Paragraph;
use Xen3r0\Adf\Parser\Adf\AdfParser;

class AdfParserTest extends TestCase
{
    public function testParseValidDocument(): void
    {
        $json = json_encode([
            'version' => 1,
            'type' => 'doc',
            'content' => [
                ['type' => 'paragraph', 'content' => [['type' => 'text', 'text' => 'Hello']]],
            ],
        ]);
        $this->assertIsString($json);

        $document = (new AdfParser())->parse($json);

        $this->assertSame(1, $document->getVersion());
        $this->assertCount(1, $document->getContent());
        $this->assertInstanceOf(Paragraph::class, $document->getContent()[0]);
    }

    public function testParseThrowsOnMalformedJson(): void
    {
        $this->expectException(InvalidAdfDocumentException::class);

        (new AdfParser())->parse('{not json');
    }

    public function testParseThrowsWhenTopLevelIsNotAnObjectOrArray(): void
    {
        $this->expectException(InvalidAdfDocumentException::class);

        (new AdfParser())->parse('"just a string"');
    }
}
