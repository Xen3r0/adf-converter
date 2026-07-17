<?php

declare(strict_types=1);

namespace Xen3r0\Adf\Parser\Markdown;

use League\CommonMark\ConverterInterface;
use League\CommonMark\GithubFlavoredMarkdownConverter;
use Xen3r0\Adf\Node\Block\Document;
use Xen3r0\Adf\Parser\Html\HtmlParser;
use Xen3r0\Adf\Parser\ParserInterface;

final class MarkdownParser implements ParserInterface
{
    private ConverterInterface $converter;
    private HtmlParser $htmlParser;

    public function __construct(?ConverterInterface $converter = null, ?HtmlParser $htmlParser = null)
    {
        $this->converter = $converter ?? new GithubFlavoredMarkdownConverter();
        $this->htmlParser = $htmlParser ?? new HtmlParser();
    }

    public function parse(string $content): Document
    {
        return $this->htmlParser->parse((string) $this->converter->convert($content));
    }
}
