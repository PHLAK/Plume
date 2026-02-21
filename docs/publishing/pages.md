---
icon: memo
layout:
  width: default
  title:
    visible: true
  description:
    visible: false
  tableOfContents:
    visible: true
  outline:
    visible: true
  pagination:
    visible: true
  metadata:
    visible: true
  tags:
    visible: true
---

# Pages



<pre><code>/path/to/plume
├── data
<strong>│   ├── <a data-footnote-ref href="#user-content-fn-1">pages</a>
</strong>│   │   ├── about-this-blog.md
│   │   └── something-else.md
│   └── [other data]
└── docker-compose.yaml
</code></pre>

### Front Matter

Pages, like posts, _must_ contain some metadata like a title and the link text. This metadata is defined as "front matter", that is, some YAML set between triple-dashes (i.e. `---`) and must be the first thing in the file.

{% code title="about-this-blog.md" %}
```markdown
---
title: About this Blog
link: About
weight: 200
---

Your page contents goes here...
```
{% endcode %}

#### Metadata Fields

The following metadata fields are supported for pages.

<table><thead><tr><th>Key</th><th>Type</th><th width="119.5" align="center">Required</th><th>Details</th></tr></thead><tbody><tr><td><code>title</code></td><td><code>string</code></td><td align="center">✅️</td><td>Page title</td></tr><tr><td><code>link</code></td><td><code>string</code></td><td align="center">✅️</td><td>Navigation link text</td></tr><tr><td><code>weight</code></td><td><code>int</code></td><td align="center">❌️</td><td>Sort weight. Lower values will be sorted before higher value.</td></tr></tbody></table>

{% hint style="info" %}
Metadata fields that are not required may be omitted.
{% endhint %}

### Markdown

Immediately following the front matter should be your page contents, authored in Markdown format. See the [Markdown documentation](markdown.md) for more information about authoring with Markdown.

### Publishing

{% hint style="warning" icon="traffic-cone" %}
This section is a work in progress.
{% endhint %}

#### Publish a single page

```
plume publish:page <slug>
```

#### Publish all pages

```
plume publish:pages
```

[^1]: Put your pages in this folder
