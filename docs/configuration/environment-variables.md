# Environment Variables

Plume utilizes environment variables for the majority of its configuration.
This page aims to be an exhaustive list of supported environment variables and
their functions.

## Bootstrap Configuration

These configuration options are special configuration options only configurable
through environment variables (e.g. through the `.env` file).

> [!NOTE]
> There are no corresponding configuration options for these values in the 
> `app/config` definitions because they are applied before the application
> container (and configuration) is loaded.

### `COMPILE_CONTAINER`

Whether or not the application container will be compiled. When set to `false`
the container will _not_ be compiled and cached. If left unset the container
will be compiled and cached on first run and the cached container will be reused
on subsequent requests. 

> [!WARNING]
> Generally this should not be changed. Disabling this comes with a (negligible)
> performance hit. However, it may be necessary to modify this when running in a
> read-only filesystem. 

- **Possible Values:** `false` or `<unset>`
- **Default Value:** `<unset>`

## Runtime Configuration

### `APP_DEBUG`

Enable application debugging and display error messages.

> [!DANGER]
> It is recommended that debug remains OFF unless troubleshooting an issue.
> Leaving this enabled WILL cause leakage of sensitive server information.

- **Possible Values:** `true` or `false`
- **Default Value:** `false`

### `AUTHORS_LINK`

Whether or not to show the "Authors" navigation link when there are multiple
unique post authors.

- **Possible Values:** `true` or `false`
- **Default Value:** `true`

### `DATE_FORMAT`

Default date format.

- **Possible Values:** See the [PHP \`date\` format documentation](https://www.php.net/manual/en/function.date.php#refsect1-function.date-parameters)
  for possible values.
- **Default Value:** `Y-m-d H:i:s`

### `FEATURED_IMAGES`

Controls the vertical sizing of featured post images.

- **Possible Values:** `full` (display the full height images) or `collapsed` 
  (constrain the images height)
- **Default Value:** `collapsed`

### `META_DESCRIPTION`

[Meta tag](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/meta)
description text.

- **Possible Values:** Any string
- **Default Value:** `Yet another amazing blog, published with Plume.`

### `PAGINATION`

Whether or not post pagination is enabled.

- **Possible Values:** `true` or `false`
- **Default Value:** `true`

### `POSTS_PER_PAGE`

The number of posts to display per page when pagination is enabled.

- **Possible Values:** Any positive integer
- **Default Value:** `10`

### `SHIKI_THEME_ID`

The Shiki theme ID used for syntax highlighting in rendered code blocks.

- **Possible Values:** See the [Shiki themes documentation](https://shiki.style/themes)
  for a list of themes to choose from.
- **Default Value:** `catppuccin-frappe`

### `SITE_TITLE`

The title of your blog. This will be displayed in the browser tab/title bar
along with the current path.

- **Possible Values:** Any string
- **Default Value:** `Yet another amazing blog`

### `TAGS_LINK`

Whether or not to show the "Tags" navigation link when there are multiple unique
post tags.

- **Possible Values:** `true` or `false`
- **Default Value:** `true`

### `THEME`

Custom theme ID. Specifies the custom application theme to use.

- **Possible Values:** `string` or `null`
- **Default Value:** `null`

### `TIMEZONE`

Timezone used for date formatting.

- **Possible Values:** See the [PHP List of Supported Timezones](https://www.php.net/manual/en/timezones.php)
  for a list of supported values.
- **Default Value:** The server's timezone

### `USAGE_REPORTING`

Enable anonymized usage reporting.

> [!INFO]
> The data collected contains aggregated metrics and does not identify individual users.

- **Possible Values:** `true` or `false`
- **Default Value:** `true`

## Cache Configuration

### `CACHE_DRIVER`

The application cache driver. Setting this value to `array` will disable caching
across requests. Additional driver-specific options may be required with certain
values.

- **Possible Values:** `apcu`, `array`, `file`, `memcached`, `redis`, `php-file`, `valkey`
- **Default Value:** `file`

### `CACHE_LIFETIME`

The app cache lifetime (in seconds). If set to `0`, cache indefinitely.

- **Possible Values:** Any positive integer
- **Default Value:** `0`

### `CACHE_LOTTERY`

Some cache drivers require manually pruning the cache periodically to remove
expired items. This is the percentage chance (out of 100) of a request "winning"
the lottery causing the cache to be pruned.

- **Possible Values:** Any integer between `1` and `100`
- **Default Value:** `2`

### `VIEW_CACHE`

Path to the view cache directory. Set to `false` to disable view caching
entirely. The view cache is separate from the application cache defined above.

- **Possible Values:** A directory path as a string or `false`
- **Default Value:** `{cache_path}/views`

## Memcached Configuration

> [!TIP]
> Advanced Memcached configuration is possible via manual modification of the
> `memcached_config` option found in `config/cache.php`.

### `MEMCACHED_HOST`

The Memcached server hostname or IP address.

- **Possible Values:** A hostname or IP address as a string
- **Default Value:** `localhost`

### `MEMCACHED_PORT`

The Memcached server port.

- **Possible Values:** Any valid port as an integer (`0` to `65535`)
- **Default Value:** `11211`

## Redis Configuration

> [!TIP]
> Advanced Redis configuration is possible via manual modification of the
> `redis_config` option found in `config/cache.php`.

### `REDIS_HOST`

The Redis server hostname or IP address.

- **Possible Values:** A hostname or IP address as a string
- **Default Value:** `localhost`

### `REDIS_PORT`

The Redis server port.

- **Possible Values:** Any valid port as an integer (`0` to `65535`)
- **Default Value:** `6379`
