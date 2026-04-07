# Configuration Overview

## Environment Variables

Plume allows customization through configuration. You can configure Plume in a
few different ways. The simplest and _recommended_ way to configure Plume is
through environment variables. You can find a list of environment variables and
their function in the [Environment Variables](environment-variables.md)
documentation.

### Plume Compose

When using the [Plume Compose](/installation#plume-compose) installation method
enviroment variables should be defined in the `environment.d/plume.env` file.

::: code-group
```yaml [plume.env]
SITE_TITLE: My Amazing Blog
TIMEZONE: America/Phoenix
```
:::

> [!IMPORTANT]
> After modifying `environment.d/plume.env` you must restart your containers
> (i.e. `docker compose up -d`) for the changes to apply.

### Docker Compose

For a manual [Docker Compose](/installation#docker-compose) installation, you
may define environment variables with the [`environment`](https://docs.docker.com/reference/compose-file/services/#environment)
attribute in your `docker-compose.yaml` file.

::: code-group
```yaml [docker-compose.yaml]
services:

  plume:
    image: phlak/plume:<version>
    environment: # [!code focus]
      SITE_TITLE: My Amazing Blog # [!code focus]
      TIMEZONE: America/Phoenix # [!code focus]
    ports:
      - <host_port>:80
    volumes:
      - ./data:/data
    restart: unless-stopped
```
:::

> [!IMPORTANT]
> After modifying your `docker-compose.yaml` file  you must restart your
> containers (i.e. `docker compose up -d`) for the changes to apply.

### Docker Run

You may pass environment variables to the `docker run` command via the `--env`
flag. Multiple environment variables can be set by passing the `--env` flag
multiple times for each option.

```console
docker run --detach --publish 8080:80 --volume ./data:/data \
    --env SITE_TITLE="My Amazing Blog" --env TIMEZONE="America/Phoenix" \ // [!code focus]
    phlak/plume:latest
```

### Manual Installation

When Plume is installed manually, environment variables should be defined in the
`.env` file. You may copy `.env.example` to `.env` if the file is missing.

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

## User Customization

> [!WARNING] Advanced user customization is currently in development.
> 
> If there's something you would like to be able to customize that isn't
> currently possible, open a feature request.

## Custom JavaScript and CSS

Arbitrary code like CSS & JavaScript may be included in the HTML output of your
site through the `customizations.html` file. This is particularly useful for
including analytics tracking code from [Google
Analytics](https://analytics.google.com), [Matomo
Analytics](https://matomo.org), [Umami Analytics](https://umami.is) or other
similar analytics service.

To inject your customization into your page, create a file named
`customizations.html` in the data directory and place your code into this file.

```text{4}
/path/to/plume
├── data
│   ├── [other files and folders]
│   └── customizations.html
└── docker-compose.yaml
```

::: code-group
```html [customizations.html]
<!-- Put your custom code here -->
```
:::
