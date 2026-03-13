# Pages

```text{3}
/path/to/plume
├── data
│   ├── pages
│   │   ├── about-this-blog.md
│   │   └── something-else.md
│   └── [other data]
└── docker-compose.yaml
```

### Front Matter

Pages, like posts, _must_ contain some metadata like a title and the link text. This metadata is defined as "front matter", that is, some YAML set between triple-dashes (i.e. `---`) and must be the first thing in the file.

::: code-group
```markdown [about-this-blog.md]
---
title: About this Blog
link: About
weight: 200
---

Your page contents goes here...
```
:::

#### Metadata Fields

The following metadata fields are supported for pages.

| Key       | Type     | Required  | Details                                          |
| --------- | -------- | :-------: | ------------------------------------------------ |
|  `title`  | `string` |     ✅️    | Page title                                       |
|  `link`   | `string` |     ✅️    | Navigation link text                             |
|  `weight` | `int`    |     ❌️    | Lower values will be sorted before higher value. |

> [!TIP]
> Metadata fields that are not required may be omitted.

### Markdown

Immediately following the front matter should be your page contents, authored in Markdown format. See the [Markdown documentation](markdown.md) for more information about authoring with Markdown.

### Publishing

After adding or editing a page it must be published. Publishing a page will render the contents, and update the page cache.

#### Publish a single page

::: code-group
```console [Docker Compose]
docker compose run --rm php plume publish:page <slug>
```

```console [Manual]
php plume publish:page <slug>
```
:::

#### Publish all pages

::: code-group
```console [Docker Compose]
docker compose run --rm php plume publish:pages
```

```console [Manual]
php plume publish:pages
```
:::
