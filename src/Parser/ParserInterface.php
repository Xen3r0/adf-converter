<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Parser;

use Xen3r0\Adf\Node\Block\Document;

interface ParserInterface
{
    public function parse(string $content): Document;
}
