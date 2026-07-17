# Supported elements

The library models the ADF document as a tree of typed node objects. This page
lists every node and mark it understands, together with the ADF `type` string,
the PHP class, and how the HTML exporter renders it.

## Block nodes

| ADF type      | PHP class (`Xen3r0\Adf\Node\Block\…`) | HTML output                              |
| ------------- | ------------------------------------- | ---------------------------------------- |
| `doc`         | `Document`                            | (document root)                          |
| `paragraph`   | `Paragraph`                           | `<p>…</p>`                                |
| `heading`     | `Heading`                             | `<h1>`–`<h6>`                             |
| `blockquote`  | `Blockquote`                          | `<blockquote>…</blockquote>`             |
| `bulletList`  | `BulletList`                          | `<ul>…</ul>`                             |
| `orderedList` | `OrderedList`                         | `<ol start="…">…</ol>`                   |
| `codeBlock`   | `CodeBlock`                           | `<pre><code class="language-…">…</code></pre>` |
| `rule`        | `Rule`                                | `<hr />`                                 |
| `panel`       | `Panel`                               | `<div class="panel panel-…">…</div>`     |
| `expand`      | `Expand`                              | `<details><summary>…</summary>…</details>` |
| `table`       | `Table`                               | `<table><tbody>…</tbody></table>`        |
| `mediaGroup`  | `MediaGroup`                          | `<div class="media-group">…</div>`       |
| `mediaSingle` | `MediaSingle`                         | (renders its media children)             |

## Child nodes

| ADF type      | PHP class (`Xen3r0\Adf\Node\Child\…`) | HTML output                       |
| ------------- | ------------------------------------- | --------------------------------- |
| `listItem`    | `ListItem`                            | `<li>…</li>`                      |
| `tableRow`    | `TableRow`                            | `<tr>…</tr>`                      |
| `tableCell`   | `TableCell`                           | `<td colspan rowspan>…</td>`     |
| `tableHeader` | `TableHeader`                         | `<th colspan rowspan>…</th>`     |
| `media`       | `Media`                               | `<img src alt width height />`   |

## Inline nodes

| ADF type     | PHP class (`Xen3r0\Adf\Node\Inline\…`) | HTML output                                 |
| ------------ | -------------------------------------- | ------------------------------------------- |
| `text`       | `Text`                                 | escaped text, wrapped by its marks          |
| `hardBreak`  | `Hardbreak`                            | `<br />`                                     |
| `mention`    | `Mention`                              | `<span class="mention" data-id="…">…</span>` |
| `emoji`      | `Emoji`                                | the emoji character or short name           |
| `date`       | `Date`                                 | `<time datetime="…">…</time>`               |
| `status`     | `Status`                               | `<span class="status status-…">…</span>`    |
| `inlineCard` | `InlineCard`                           | `<a href="…">…</a>`                          |

## Marks

Marks decorate `text` nodes. Multiple marks stack (for example bold + link).

| ADF type          | PHP class (`Xen3r0\Adf\Node\Mark\…`) | HTML output                                    |
| ----------------- | ------------------------------------ | ---------------------------------------------- |
| `strong`          | `Strong`                             | `<strong>…</strong>`                           |
| `em`              | `Em`                                 | `<em>…</em>`                                    |
| `code`            | `Code`                               | `<code>…</code>`                               |
| `strike`          | `Strike`                             | `<del>…</del>`                                 |
| `underline`       | `Underline`                          | `<u>…</u>`                                      |
| `subsup`          | `Subsup`                             | `<sub>…</sub>` or `<sup>…</sup>`               |
| `textColor`       | `TextColor`                          | `<span style="color:…">…</span>`              |
| `backgroundColor` | `BackgroundColor`                    | `<span style="background-color:…">…</span>`   |
| `link`            | `Link`                               | `<a href="…" title="…">…</a>`                 |

The `subsup` type is restricted to `sub`/`sup` and link URLs are sanitised on
export — see [Security](security.md).

## Enums

The ADF type strings above are also available as PHP enums:

- `Xen3r0\Adf\Enum\NodeType`
- `Xen3r0\Adf\Enum\MarkType`
