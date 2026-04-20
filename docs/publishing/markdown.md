# Markdown

Plume supports the extended set of Markdown known as
[GitHub Flavored Markdown](https://github.github.com/gfm/).
For an overview of the basic syntax of Markdown check out the
[Markdown Basic Syntax](https://www.markdownguide.org/basic-syntax/) guide.

In addition to the GitHub Flavored Markdown spec Plume supports some additional
features detailed below.

## Table of Contents

A table of contents can be rendered by adding the `[[TOC]]` placeholder to your
Markdown file where you would like it to be rendered. The placeholder will be
replaced with a list of links to the header tags (`h1` - `h6`) within the
document when published.

:::code-group
```markdown [dont-panic.md]
---
title: Don't Panic
published: 1970-01-01 00:00:00
---

[[TOC]]

Your post contents goes here...
```
:::

## Alerts

"Alerts" (a.k.a. callouts or admonitions) are an extension of the blockquote
syntax useful for emphasizing important information. Alerts render as block
quotes with a distinctive color and icon to emphasize the content.

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

![Markdown Alerts](/images/alerts.png)

## Images

Images can be uploaded to the `data/files` folder and referenced in your post
markdown or raw HTML.

```markdown
![Image Alt Text](/files/some-image.png)
```

or

```html
<img src="/files/some-image.png" alt="Image Alt Text"></img>
```

Make sure you preface the file path with a forward slash (i.e. `/`).

You may also organize your images in arbitrary sub-folders as long as your links
follow suit. For example, an image at `data/images/some-post/example.png` can be
referenced like so.

```markdown
![Example Image](/files/images/some-post/example.png)
```

## Embeds

> [!NOTE] Coming soon...
