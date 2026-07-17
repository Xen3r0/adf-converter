# Exporting

All exporters implement `Xen3r0\Adf\Exporter\ExporterInterface`:

```php
interface ExporterInterface
{
    public function export(Node $node): string;
}
```

`export()` accepts any node, but you will normally pass the `Document` returned
by a parser.

## HTML exporter

`Xen3r0\Adf\Exporter\Html\HtmlExporter` renders the node tree to HTML. All text
content is escaped and all link URLs are sanitised (see [Security](security.md)).

```php
use Xen3r0\Adf\Parser\Adf\AdfParser;
use Xen3r0\Adf\Exporter\Html\HtmlExporter;

$document = (new AdfParser())->parse($adfJson);

echo (new HtmlExporter())->export($document);
// <h1>Hello</h1><p>world <strong>bold</strong> and <a href="https://example.com">link</a></p>
```

## Markdown exporter

`Xen3r0\Adf\Exporter\Markdown\MarkdownExporter` renders the node tree to
Markdown. Elements that have no Markdown equivalent (underline, sub/sup, text
colours, expand blocks) fall back to a small, safe subset of inline HTML.

```php
use Xen3r0\Adf\Exporter\Markdown\MarkdownExporter;

echo (new MarkdownExporter())->export($document);
// # Hello
//
// world **bold** and [link](https://example.com)
```

## Back to ADF JSON

`Document` implements `JsonSerializable`, so there is no dedicated ADF exporter —
just encode it:

```php
use Xen3r0\Adf\Parser\Html\HtmlParser;

$document = (new HtmlParser())->parse('<h1>Title</h1><p>a <em>b</em></p>');

echo json_encode($document, JSON_PRETTY_PRINT);
```

```json
{
    "version": 1,
    "type": "doc",
    "content": [
        {
            "type": "heading",
            "attrs": { "level": 1 },
            "content": [ { "type": "text", "text": "Title" } ]
        },
        {
            "type": "paragraph",
            "content": [
                { "type": "text", "text": "a " },
                { "type": "text", "text": "b", "marks": [ { "type": "em" } ] }
            ]
        }
    ]
}
```

## Errors

If a node or mark class has no matching branch in an exporter, a
`Xen3r0\Adf\Exception\UnsupportedExportFormatException` is thrown with the
offending class name. In normal use — exporting a tree produced by one of the
bundled parsers — this cannot happen; it only occurs if you build the tree
manually with a custom node type.

## Working with the node tree directly

You are not limited to the parsers. You can build or modify the tree by hand
before exporting:

```php
use Xen3r0\Adf\Node\Block\Document;
use Xen3r0\Adf\Node\Block\Paragraph;
use Xen3r0\Adf\Node\Inline\Text;

$document = (new Document())
    ->addContent((new Paragraph())->addContent(new Text('Built by hand')));
```

See [Supported elements](supported-elements.md) for the full list of node and
mark classes.
