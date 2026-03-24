# Installation

## Plume Compose

> [!IMPORTANT] Requirements
> - [Docker Compose](https://docs.docker.com/compose/)
> - [Git Version Control](https://git-scm.com)
> - [GNU Make](https://www.gnu.org/software/make/)

For a long-term installation we provide [Plume Compose](https://github.com/PHLAK/plume-compose)
as a quick and easy way of getting up and running with a pre-configured
[Docker Compose](https://docs.docker.com/compose/) configuration.

1. Clone the repository to a location of your choosing

    ```console
    cd /your/chosen/path/
    git clone git@github.com:PHLAK/plume-compose.git
    ````

2. Switch to the `plume-compose` directory and initialize the configuration files

    ```console
    cd plume-compose
    make init
    ```

4. Modify the environment variables in `.env` for your installation

5. Run `docker compose config` to validate and confirm your configuration

6. Run `docker compose up -d` to start the containers

## Docker Compose

> [!IMPORTANT] Requirements
> - [Docker Compose](https://docs.docker.com/compose/)

If you prefer a maunal [Docker Compose](https://docs.docker.com/compose/)
installation you may create a directory for your Plume app and, in that
directory, add a `docker-compose.yaml` file and a folder (e.g. `data`) to 
contain your data.

```text{4}
/path/to/plume
├── data
│   └── [your data will go here]
└── docker-compose.yaml
```

::: code-group
```yaml [docker-compose.yaml]
services:

  plume:
    image: phlak/plume:<version>
    environment:
      # SITE_TITLE: My Amazing Blog
      # TIMEZONE: America/Phoenix
      # See configuration docs for additional variables
    ports:
      - <host_port>:80
    volumes:
      - ./data:/data
    restart: unless-stopped
```
:::

> [!IMPORTANT]
> Replace `<version>` with the version of Plume you'd like to run (e.g. `1.0.5`, `1.0` or `1`)
>
> Replace `<host_port>` with the port on which you would like the application to be exposed.

> [!TIP]
> See [Environment Variables](configuration/environment-variables.md) for a full
> list of the available environment variables.

Once created, start the container by running `docker compose up -d` from the
same directory as the `docker-compose.yaml` file.

## Docker Run

> [!IMPORTANT] Requirements
> - [Docker](https://docs.docker.com)

You may alternatively use `docker run` to launch a stand-alone Docker container
from the official Docker image.

This is a good way to test Plume for the first time but is _not recommended_ for
long-term use. Instead we recommend using either the [Plume Compose](#plume-compose)
or [Docker Compose](#docker-compose) installation method instead.

```console
docker run --detach [--env ENVIRONMENT_VARIABLE=value] \
    --volume <host_path>:/data --publish <host_port>:80 \
    phlak/plume:latest
```

> [!IMPORTANT]
> Replace `<host_path>` with the path to a directory where your posts, pages and
> additional data will be stored.
>
> Replace `<host_port>` with the port on which you would like the application to
> be exposed.

> [!TIP]
> You may pass multiple environment variables by repeating the `--env` flag.

## Manual Installation

> [!IMPORTANT] Requirements
> - [PHP](https://www.php.net)
> - A web server capable of serving PHP (e.g NGINX, Apache, etc.)

> [!DANGER] This is not a recommended installation method
> Installing manually _will_ require more work to update between versions.

> [!NOTE] More details coming soon including examples for
>
> - NGINX
> - Apache
> - Caddy
