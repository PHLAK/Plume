# Configuration Overview

Plume allows customization through configuration. You can configure Plume in a few different ways. The simplest and _recommended_ way to configure Plume is through environment variables.

## The `.env` File

Most configuration is possible via the `.env` file. You may define environment variables and their value in this file.

> [!SUCCESS]
> This is the recommended method for configuring your app.

To get started:

1. Copy `.env.example` to `.env`
2. Edit the configuration values in `.env`

```text{5,6}
/path/to/plume
├── data
│   └── [your data]
├── docker-compose.yaml
├── .env
└── .env.example
```

The default `.env` file should look something like this:

::: code-group
```dotenv [.env]
# SITE_TITLE="Yet another amazing blog"
# META_DESCRIPTION="Yet another amazing blog, published with Plume."

# PAGINATION=true
# POSTS_PER_PAGE=10

# FEATURED_IMAGES=collapsed

# TAGS_LINK=true
# AUTHORS_LINK=true

# DATE_FORMAT='Y-m-d H:i:s'
# TIMEZONE=

# USAGE_REPORTING=true
```
:::

> [!TIP]
> You can find a list of environment variables and their function in the [Environment Variables](environment-variables.md) documentation.

## User Customization

> [!WARNING] Advanced user customization is currently in development.
> 
> If there's something you would like to be able to customize that isn't currently possible, open a feature request.

## Custom JavaScript and CSS

Arbitrary code like CSS & JavaScript may be included in the HTML output of your site through the `customizations` file. This is particularly useful for including analytics tracking code from [Google Analytics](https://analytics.google.com), [Matomo Analytics](https://matomo.org), [Umami Analytics](https://umami.is) or other similar analytics service.

To inject your customization into your page, create a file named `customizations` in the data directory and place your code into this file.

```text{4}
/path/to/plume
├── data
│   ├── [other files and folders]
│   └── customizations
└── docker-compose.yaml
```

::: code-group
```html [customizations]
<!-- Put your custom code here -->
```
:::

## Caching

> [!INFO]
> Coming soon...
