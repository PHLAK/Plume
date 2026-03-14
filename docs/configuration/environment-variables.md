# Environment Variables

Plume utilizes environment variables for the majority of it's configuration.
This page aims to be an exhaustive list of supported environment variables and
their functions.

## `APP_DEBUG`

Enable application debugging and display error messages.

> [!DANGER]
> It is recommended that debug remains OFF unless troubleshooting an issue.
> Leaving this enabled WILL cause leakage of sensitive server information.

- **Possible Values:** `true` or `false`
- **Default Value:** `false`

## `AUTHORS_LINK`

Whether or not to show the "Authors" navigation link when there are multiple
unique post authors.

- **Possible Values:** `true` or `false`
- **Default Value:** `true`

## `DATE_FORMAT`

Default date format.

- **Possible Values:** See the [PHP \`date\` format documentation](https://www.php.net/manual/en/function.date.php#refsect1-function.date-parameters) for possible values.
- **Default Value:** `Y-m-d H:i:s`

## `FEATURED_IMAGES`

Controls the vertical sizing of featured post images.

- **Possible Values:** `full` (display the full height images) or `collapsed` (constrain the images height)
- **Default Value:** `collapsed`

## `META_DESCRIPTION`

[Meta tag](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/meta) description text.

- **Possible Values:** Any string
- **Default Value:** `Yet another amazing blog, published with Plume.`

## `PAGINATION`

Whether or not post pagination is enabled.

- **Possible Values:** `true` or `false`
- **Default Value:** `true`

## `POSTS_PER_PAGE`

The number of posts to display per page when pagination is enabled.

- **Possible Values:** Any positive integer
- **Default Value:** `10`

## `SHIKI_THEME_ID`

The Shiki theme ID used for syntax highlighting in rendered code blocks.

- **Possible Values:** See the [Shiki themes documentation](https://shiki.style/themes) for a list of themes to choose from.
- **Default Value:** `catppuccin-frappe`

## `SITE_TITLE`

The title of your blog. This will be displayed in the browser tab/title bar
along with the current path.

- **Possible Values:** Any string
- **Default Value:** `Yet another amazing blog`

## `TAGS_LINK`

Whether or not to show the "Tags" navigation link when there are multiple unique
post tags.

- **Possible Values:** `true` or `false`
- **Default Value:** `true`

## `TIMEZONE`

Timezone used for date formatting.

- **Possible Values:** See the [PHP List of Supported Timezones](https://www.php.net/manual/en/timezones.php) for a list of supported values.
- **Default Value:** The server's timezone

## `USAGE_REPORTING`

Enable anonymized usage reporting.

> [!INFO]
> The data collected contains aggregated metrics and does not identify individual users.

- **Possible Values:** `true` or `false`
- **Default Value:** `true`
