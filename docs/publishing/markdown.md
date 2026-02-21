---
icon: markdown
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

# Markdown

Plume supports the extended set of Markdown known as [GitHub Flavored Markdown](https://github.github.com/gfm/). See [here](https://www.markdownguide.org/basic-syntax/) for an overview of the basic syntax of Markdown.

### Alerts

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

### Embeds
