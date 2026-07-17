# Parsing

All parsers implement `Xen3r0\Adf\Parser\ParserInterface`:

```php
interface ParserInterface
{
    public function parse(string $content): Document;
}
```

They all return a `Xen3r0\Adf\Node\Block\Document`, so the parser is
interchangeable — pick the one that matches your input format.

## ADF parser

`Xen3r0\Adf\Parser\Adf\AdfParser` decodes an ADF JSON string into the node tree.
This is what you use for the rich-text payloads returned by the Jira and
Confluence REST APIs.

```php
use Xen3r0\Adf\Parser\Adf\AdfParser;

$document = (new AdfParser())->parse($adfJson);
```

If the string is not valid JSON (or does not decode to an array), a
`Xen3r0\Adf\Exception\InvalidAdfDocumentException` is thrown.

## HTML parser

`Xen3r0\Adf\Parser\Html\HtmlParser` reads an HTML fragment and maps recognised
tags onto ADF nodes. It uses PHP's built-in DOM extension (`DOMDocument::loadHTML`),
so it does not require a network connection and does not resolve external
entities.

```php
use Xen3r0\Adf\Parser\Html\HtmlParser;

$document = (new HtmlParser())->parse('<h1>Title</h1><p>a <em>b</em></p>');
```

Recognised block tags include `h1`–`h6`, `p`, `blockquote`, `ul`, `ol`, `pre`,
`hr`, `table`, `details`, `img`, and `div` (with `panel-*`, `media-group` and
`media-single` classes). Inline tags include `strong`/`b`, `em`/`i`, `code`,
`del`/`s`/`strike`, `u`, `sup`, `sub`, `a`, `br`, `time`, and `span` (for
mentions, statuses and text/background colours). Unknown tags are transparently
unwrapped and their children are parsed.

## Markdown parser

`Xen3r0\Adf\Parser\Markdown\MarkdownParser` converts Markdown to HTML with
[league/commonmark] (GitHub Flavored Markdown by default) and then runs the
result through the HTML parser.

```php
use Xen3r0\Adf\Parser\Markdown\MarkdownParser;

$document = (new MarkdownParser())->parse("# Title\n\nsome **bold** text");
```

You can inject a custom converter or HTML parser through the constructor — for
example to enable additional CommonMark extensions:

```php
use League\CommonMark\MarkdownConverter;

$parser = new MarkdownParser($myConverter);
```

Both arguments are optional and default to a `GithubFlavoredMarkdownConverter`
and a fresh `HtmlParser`.

[league/commonmark]: https://commonmark.thephpleague.com/
