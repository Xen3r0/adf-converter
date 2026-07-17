<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Exporter;

use Xen3r0\Adf\Node\Node;

interface ExporterInterface
{
    public function export(Node $node): string;
}
