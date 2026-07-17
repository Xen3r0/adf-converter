# Getting started

## Installation

Install the library with Composer:

```bash
composer require xen3r0/adf-converter
```

Requirements:

- PHP >= 8.2
- The `ext-dom` and `ext-json` extensions (both are enabled by default in most
  PHP builds)

## The core flow

Every conversion goes through the same two steps:

1. A **parser** reads a string (ADF JSON, HTML or Markdown) and produces a
   `Xen3r0\Adf\Node\Block\Document` — the root of a typed node tree.
2. An **exporter** takes any node and renders it back to a string (HTML or
   Markdown). The `Document` is also `JsonSerializable`, so `json_encode()`
   gives you back ADF JSON.

```
string ──parse──▶ Document (AST) ──export──▶ string
                        │
                        └── json_encode() ──▶ ADF JSON
```

Because parsing and exporting are decoupled, any input format can be turned into
any output format by pairing the right parser with the right exporter.

## A first conversion

```php
use Xen3r0\Adf\Parser\Adf\AdfParser;
use Xen3r0\Adf\Exporter\Markdown\MarkdownExporter;

$document = (new AdfParser())->parse($adfJson);
$markdown = (new MarkdownExporter())->export($document);
```

## Available classes

Parsers (all implement `Xen3r0\Adf\Parser\ParserInterface`):

| Class                                        | Input    |
| -------------------------------------------- | -------- |
| `Xen3r0\Adf\Parser\Adf\AdfParser`            | ADF JSON |
| `Xen3r0\Adf\Parser\Html\HtmlParser`          | HTML     |
| `Xen3r0\Adf\Parser\Markdown\MarkdownParser`  | Markdown |

Exporters (all implement `Xen3r0\Adf\Exporter\ExporterInterface`):

| Class                                            | Output   |
| ------------------------------------------------ | -------- |
| `Xen3r0\Adf\Exporter\Html\HtmlExporter`          | HTML     |
| `Xen3r0\Adf\Exporter\Markdown\MarkdownExporter`  | Markdown |

Continue with [Parsing](parsing.md) and [Exporting](exporting.md).
