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
    git clone https://github.com/PHLAK/plume-compose.git
    ```

2. Switch to the `plume-compose` directory and initialize the configuration files

    ```console
    cd plume-compose
    make init
    ```

3. Modify the environment variables in `.env` for your installation

4. Run `docker compose config` to validate and confirm your configuration

5. Run `docker compose up -d` to start the containers

## Docker Compose

> [!IMPORTANT] Requirements
> - [Docker Compose](https://docs.docker.com/compose/)

If you prefer a manual [Docker Compose](https://docs.docker.com/compose/)
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
    image: phlak/plume:latest
    environment:
      SITE_TITLE: My Amazing Blog
      # See configuration docs for additional variables
    ports:
      - <host_port>:80
    volumes:
      - ./data:/var/www/html/data
      - ./themes:/var/www/html/themes
      - plume-cache:/var/www/html/cache/app
    user: www-data
    restart: unless-stopped

volumes:
  plume-cache: {}
```
:::

> [!IMPORTANT]
> Replace `<host_port>` with the port on which you would like the application to be exposed.

> [!TIP]
> See [Environment Variables](configuration/environment-variables.md) for a full
> list of the available environment variables.

Once created, start the container by running `docker compose up -d` from the
same directory as the `docker-compose.yaml` file.

## Docker Run

> [!IMPORTANT] Requirements
> - [Docker](https://docs.docker.com)

You may use `docker run` to launch a stand-alone Docker container from the
official Docker image. This is a good way to test Plume for the first time but
is _not recommended_ for long-term use. Instead we recommend using either the
[Plume Compose](#plume-compose) or [Docker Compose](#docker-compose)
installation method instead.

```console
docker run --detach --publish <host_port>:80 \
    [--env ENVIRONMENT_VARIABLE=value] \
    --volume ./data:/var/www/html/data \
    phlak/plume:latest
```

> [!IMPORTANT]
> Replace `<host_port>` with the port on which you would like the application to
> be exposed.

> [!TIP]
> You may pass multiple environment variables by repeating the `--env` flag.

## Reverse Proxy

It's recommended to run Plume behind a reverse proxy. The following examples
assume Plume is accessible on the host at `127.0.0.1:8076`.

> [!TIP]
> Replace `8076` with the port you configured for your Plume installation.

### NGINX

```nginx
server {
    listen 80;
    server_name example.com;

    location / {
        proxy_pass http://127.0.0.1:8076;

        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

### Apache

```apache
<VirtualHost *:80>
    ServerName example.com

    ProxyPass / http://127.0.0.1:8076/
    ProxyPassReverse / http://127.0.0.1:8076/
</VirtualHost>
```

### Caddy

```caddyfile
example.com

reverse_proxy 127.0.0.1:8076
```
