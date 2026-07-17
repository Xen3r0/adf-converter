# Security

This library is designed to be fed **untrusted** content — ADF documents, HTML
or Markdown authored by end users (for example the body of a Jira comment). The
exporters therefore treat their output as a security boundary.

## HTML escaping

The HTML exporter escapes all text content and every dynamic attribute value
with `htmlspecialchars()`. A text node such as `<script>alert(1)</script>` is
rendered as `&lt;script&gt;alert(1)&lt;/script&gt;` and cannot break out of its
element or attribute.

## URL scheme allowlisting

Escaping alone does not stop a `javascript:` URL, because such a string contains
no characters that `htmlspecialchars()` would encode. Both exporters therefore
pass every link URL — from `link` marks and `inlineCard` nodes — through a
scheme allowlist (`Xen3r0\Adf\Exporter\SanitizesUrls`):

- **Allowed:** `http`, `https`, `mailto`, `tel`, and relative URLs (no scheme).
- **Everything else** (`javascript:`, `data:`, `vbscript:`, …) is replaced with
  `#`.

Detection ignores whitespace and control characters, so obfuscated schemes such
as `java\tscript:` are caught too.

```php
// A malicious ADF link…
{"type":"text","text":"click","marks":[{"type":"link","attrs":{"href":"javascript:alert(1)"}}]}

// …becomes a harmless anchor:
<a href="#">click</a>
```

The same protection is applied by the Markdown exporter, because its output is
frequently rendered back to HTML downstream.

## Restricted tag names

The `subsup` mark carries a `type` attribute. Rather than trusting it as a tag
name, the exporters map it to a fixed allowlist: any value other than `sup`
becomes `sub`. This prevents injection of an arbitrary element such as
`<img onerror=…>`.

## XXE

The HTML parser uses `DOMDocument::loadHTML()` (the HTML parser, not the XML
one) with `LIBXML_HTML_NODEFDTD`. The HTML parser does not resolve XML external
entities, so the library is not vulnerable to XXE.

## Reporting a vulnerability

Please report security issues privately through a
[GitHub security advisory](https://github.com/Xen3r0/adf-converter/security/advisories/new)
rather than a public issue.
