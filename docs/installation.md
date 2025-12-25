## Quick Start

> [!NOTE]
> Coming soon...

## Docker Compose

For a long-term installation you can use Docker Compose to install Plume. To get
started, create a directory for your Plume installation and, in that directory,
add a `docker-compose.yaml` file and a folder for your data (e.g. `data`).

```
/path/to/plume
├── data
│   └── [your data will go here]
└── docker-compose.yaml
```

### `docker-compose.yaml`
```yaml
services:

  plume:
    image: phlak/plume:<version>
    environment:
      # ...
      # TIMEZONE: America/Phoenix
      # See configuration docs for additional variables
    ports:
      - <host_port>:80
	volumes:
	  <host_path>:/data
    restart: unless-stopped
```

> [!IMPORTANT]
> Replace `<version>` with the version of Plume you'd like to run (e.g. `1.0.5`,
> `1.0` or `1`)
> 
> Replace `<host_path>` with the path to a directory where your posts, pages and
> additional data will be stored.
> 
> Replace `<host_port>` with the port on which you would like the application to
> be exposed.

Once created, start the container by running `docker compose up -d` from the
same directory as the `docker-compose.yaml` file.

> [!TIP]
> See the [Configuration Reference](https://docs.directorylister.com/configuration/configuration-reference)
> for a full list of the available environment variables.

## Docker Run

To get up and running quickly you can use `docker run` to launch a stand-alone
Docker container from the official Docker image.

> [!WARNING]
> This is a good way to test Plume for the first time but is *not recommended*
for long-term use. Instead we recommend using the Docker Compose installation
method above.

```shell
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

> [!WARNING]
> This is *not* the recommended installation method and will require more work
> to update between versions. Instead we recommend using the Docker Compose
> installation method above for quick and easy updates.

> [!NOTE]
> Coming soon...
> - Nginx
> - Apache
> - Caddy
