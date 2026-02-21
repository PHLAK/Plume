---
icon: pen-nib
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

# Posts

As a publishing platform (i.e. blog), the main purpose of Plume is to facilitate the authoring and publishing of posts. This is made possible through Markdown files with a bit of YAML sprinkled in. To get started you may add your files with an extension of `.md` in the `data/posts` directory.

<pre><code>/path/to/plume
├── data
│   ├── <a data-footnote-ref href="#user-content-fn-1">posts</a>
│   │   ├── some-amazing-post.md
│   │   └── yet-another-post.md
│   └── [other data]
└── docker-compose.yaml
</code></pre>

{% hint style="info" %}
The name of the file (not including the `.md` extension) will be used as the post "slug", the part that shows up in the URL. For example, for the file `data/posts/vogon-poetry.md` the URL would be something like `example.com/posts/vogon-poetry`.
{% endhint %}

### Front Matter

In order for a post to be recognized by Plume it _must_ contain some metadata like a title and published date. This metadata is defined as "front matter", that is, some YAML set between triple-dashes (i.e. `---`) and must be the first thing in the file.

{% code title="vogon-poetry.md" %}
```markdown
---
title: An analysis of Vogon poetry
published: 2021-02-06 08:56:42
author: Arthur Dent
image:
  url: https://example.com/image.png
  caption: Photo by [Trillina McMillan](https://example.com)
tags: [Vogons, Poetry, Art]
draft: true
---

Your post contents goes here...
```
{% endcode %}

#### Metadata Fields

The following metadata fields are supported.

<table><thead><tr><th>Key</th><th>Type</th><th width="119.5" align="center">Required</th><th>Details</th></tr></thead><tbody><tr><td><code>title</code></td><td><code>string</code></td><td align="center">✅️</td><td>Post title</td></tr><tr><td><code>published</code></td><td><code>string</code>, <code>int</code></td><td align="center">✅️</td><td>Post publish date</td></tr><tr><td><code>author</code></td><td><code>string</code></td><td align="center">❌️</td><td>Post author</td></tr><tr><td><code>image.url</code></td><td><code>string</code></td><td align="center">❌️</td><td>Reference uploaded images as <code>/files/image-name.png</code></td></tr><tr><td><code>image.caption</code></td><td><code>string</code></td><td align="center">❌️</td><td>Markdown allowed</td></tr><tr><td><code>tags</code></td><td><code>array</code> (of strings)</td><td align="center">❌️</td><td></td></tr><tr><td><code>draft</code></td><td><code>boolean</code></td><td align="center">❌️</td><td></td></tr></tbody></table>

{% hint style="info" %}
Metadata fields that are not required rest may be omitted.
{% endhint %}

### Markdown

Immediately following the front matter should be your post contents, authored in Markdown format. See the [Markdown documentation](markdown.md) for more information about authoring with Markdown.

### Publishing

{% hint style="warning" icon="traffic-cone" %}
This section is a work in progress.
{% endhint %}

#### Publish a single post

```
plume publish:post <slug>
```

#### Publish all posts

```
plume publish:posts
```

[^1]: Put your posts in this folder
