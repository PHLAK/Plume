# Posts

As a publishing platform (i.e. blog), the main purpose of Plume is to facilitate the authoring and publishing of posts. This is made possible through Markdown files with a bit of YAML sprinkled in. To get started you may add your files with an extension of `.md` in the `data/posts` directory.

```text{3}
/path/to/plume
├── data
│   ├── posts
│   │   ├── some-amazing-post.md
│   │   └── yet-another-post.md
│   └── [other data]
└── docker-compose.yaml
```

> [!TIP]
> The name of the file (not including the `.md` extension) will be used as the post "slug", the part that shows up in the URL. For example, for the file `data/posts/vogon-poetry.md` the URL would be something like `example.com/posts/vogon-poetry`.

### Front Matter

In order for a post to be recognized by Plume it _must_ contain some metadata like a title and published date. This metadata is defined as "front matter", that is, some YAML set between triple-dashes (i.e. `---`) and must be the first thing in the file.

::: code-group
```markdown [vogon-poetry.md]
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
:::

#### Metadata Fields

The following metadata fields are supported.

| Key              | Type            | Required  | Details                                              |
| ---------------- | --------------- | :-------: | ---------------------------------------------------- |
|  `title`         | `string`        |     ✅️    | Post title                                           |
|  `published`     | `string`, `int` |     ✅️    | Post publish date as datetime string or timestamp    |
|  `author`        | `string`        |     ❌️    | Post author                                          |
|  `image.url`     | `string`        |     ❌️    | Reference uploaded images as `/files/image-name.png` |
|  `image.caption` | `string`        |     ❌️    | Markdown allowed                                     |
|  `tags`          | `array`         |     ❌️    | Array of tags                                        |
|  `draft`         | `boolean`       |     ❌️    |                                                      |

> [!TIP]
> Metadata fields that are not required rest may be omitted.

### Markdown

Immediately following the front matter should be your post contents, authored in Markdown format. See the [Markdown documentation](markdown.md) for more information about authoring with Markdown.

### Publishing

After adding or editing a post it must be published.

#### Publish a single post

Publishing a single post will render the contents and update the post cache for the specified post.

::: code-group
```console [Docker Compose]
docker compose run --rm php plume publish:post <slug>
```

```console [Manual]
php plume publish:post <slug>
```
:::

#### Publish all posts

Publishing all posts will render the contents and update the post cache for all posts as well as updated the posts list cache.

::: code-group
```console [Docker Compose]
docker compose run --rm php plume publish:posts
```

```console [Manual]
php plume publish:posts
```
:::
