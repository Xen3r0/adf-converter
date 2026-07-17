<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Exporter;

trait SanitizesUrls
{
    /**
     * Neutralises dangerous URL schemes (javascript:, data:, vbscript:, …) so an
     * untrusted URL cannot execute script once the exported document is rendered
     * in a browser. Relative URLs and the safe schemes below pass through unchanged;
     * anything else is replaced with "#".
     */
    private function sanitizeUrl(string $url): string
    {
        $probe = strtolower(preg_replace('/[\x00-\x20]+/', '', $url) ?? $url);

        if (1 === preg_match('/^([a-z][a-z0-9+.-]*):/', $probe, $matches)
            && !\in_array($matches[1], ['http', 'https', 'mailto', 'tel'], true)) {
            return '#';
        }

        return $url;
    }
}
