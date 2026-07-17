<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Parser\Adf;

use Xen3r0\Adf\Exception\InvalidAdfDocumentException;
use Xen3r0\Adf\Node\Block\Document;
use Xen3r0\Adf\Parser\ParserInterface;

final class AdfParser implements ParserInterface
{
    public function parse(string $content): Document
    {
        $data = json_decode($content, true);

        if (!\is_array($data)) {
            throw new InvalidAdfDocumentException();
        }

        return Document::load($data);
    }
}
