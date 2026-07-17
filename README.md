# adf-converter

[![CI](https://github.com/Xen3r0/adf-converter/actions/workflows/ci.yml/badge.svg)](https://github.com/Xen3r0/adf-converter/actions/workflows/ci.yml)
[![Latest Version](https://img.shields.io/packagist/v/xen3r0/adf-converter.svg)](https://packagist.org/packages/xen3r0/adf-converter)
[![License](https://img.shields.io/packagist/l/xen3r0/adf-converter.svg)](LICENSE)
[![PHP Version](https://img.shields.io/packagist/dependency-v/xen3r0/adf-converter/php.svg)](composer.json)

A PHP toolkit to parse the [Atlassian Document Format (ADF)][adf] — the rich-text
format used by Jira and Confluence — and convert it to and from **HTML** and
**Markdown**.

It parses any of the three formats into a typed node tree (an AST) and exports
that tree back out, so every combination below is supported:

| From ↓ / To → | ADF (JSON) | HTML | Markdown |
| ------------- | :--------: | :--: | :------: |
| **ADF**       |     —      |  ✅  |    ✅    |
| **HTML**      |     ✅     |  ✅  |    ✅    |
| **Markdown**  |     ✅     |  ✅  |    ✅    |

## Requirements

- PHP >= 8.2
- Extensions: `ext-dom`, `ext-json`

## Installation

```bash
composer require xen3r0/adf-converter
```

## Quick start

Convert an ADF document (for example the `body` you get back from the Jira API)
to HTML:

```php
use Xen3r0\Adf\Parser\Adf\AdfParser;
use Xen3r0\Adf\Exporter\Html\HtmlExporter;

$adf = '{"version":1,"type":"doc","content":[
    {"type":"heading","attrs":{"level":1},"content":[{"type":"text","text":"Hello"}]},
    {"type":"paragraph","content":[{"type":"text","text":"world"}]}
]}';

$document = (new AdfParser())->parse($adf);
echo (new HtmlExporter())->export($document);
// <h1>Hello</h1><p>world</p>
```

Turn user-written Markdown into an ADF document ready to send to Jira:

```php
use Xen3r0\Adf\Parser\Markdown\MarkdownParser;

$document = (new MarkdownParser())->parse("# Title\n\nsome **bold** text");

echo json_encode($document); // valid ADF JSON
```

## Documentation

- [Getting started](docs/getting-started.md) — installation and the core flow
- [Parsing](docs/parsing.md) — the ADF, HTML and Markdown parsers
- [Exporting](docs/exporting.md) — the HTML and Markdown exporters, and the AST
- [Supported elements](docs/supported-elements.md) — node and mark reference
- [Security](docs/security.md) — how untrusted input is made safe
- [Development](docs/development.md) — running the tests, linters and CI locally

## Security

All exported HTML and Markdown is hardened against XSS: dangerous URL schemes
(`javascript:`, `data:`, …) are stripped from links, and text content is
escaped. See [docs/security.md](docs/security.md) for details. To report a
vulnerability, please open a [security advisory][advisory].

## License

Released under the [MIT License](LICENSE). Copyright © Manuel Santisteban.

[adf]: https://developer.atlassian.com/cloud/jira/platform/apis/document/structure/
[advisory]: https://github.com/Xen3r0/adf-converter/security/advisories/new
