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
<strong>│   ├── posts # Put your posts in this folder
</strong>│   │   ├── some-amazing-post.md
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

Immediately following the front matter should be your post contents, authored in Markdown format. Plume supports the extended set of Markdown known as [GitHub Flavored Markdown](https://github.github.com/gfm/).

#### Alerts

In addition to the GitHub Flavored Markdown spec Plume supports "Alerts" (a.k.a. callouts or admonitions), an extension of the blockquote syntax useful for emphasizing important information. Alerts render as block quotes with a distinctive color and icon to emphasize the content.

```markdown
> [!NOTE]
> Useful information that users should know, even when skimming content.

> [!TIP]
> Helpful advice for doing things better or more easily.

> [!IMPORTANT]
> Key information users need to know to achieve their goal.

> [!WARNING]
> Urgent info that needs immediate user attention to avoid problems.

> [!CAUTION]
> Advises about risks or negative outcomes of certain actions.
> This is a test of a multi-paragraph alert.
```

<figure><img src="../.gitbook/assets/alerts.png" alt=""><figcaption></figcaption></figure>

### Post Images

Images can be uploaded to the `data/files` folder and referenced in your post markdown or raw HTML.

```markdown
![Image Alt Text](/files/some-image.png)
```

or

```html
<img src="/files/some-image.png" alt="Image Alt Text"></img>
```

Make sure you preface the file path with a forward slash (i.e. `/`).

You may also organize your images in arbitrary sub-folders as long as your links follow suit. For example, an image at `data/images/some-post/example.png` can be referenced like so.

```markdown
![Example Image](/files/images/some-post/example.png)
```
