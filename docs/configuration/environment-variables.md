---
icon: square-list
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

# Environment Variables

Plume utilizes environment variables for the majority of it's configuration. This page aims to be an exhaustive list of supported environment variables and their functions.

### `APP_DEBUG`

Enable application debugging and display error messages.

{% hint style="danger" %}
It is recommended that debug remains OFF unless troubleshooting an issue. Leaving this enabled WILL cause leakage of sensitive server information.
{% endhint %}

{% tabs %}
{% tab title="Possible Values" %}
`true` or `false`
{% endtab %}

{% tab title="Default Value" %}
`false`
{% endtab %}
{% endtabs %}

### `AUTHORS_LINK`

Whether or not to show the "Authors" navigation link when there are multipe unique post authors.

{% tabs %}
{% tab title="Possible Values" %}
`true` or `false`
{% endtab %}

{% tab title="Default Value" %}
`true`&#x20;
{% endtab %}
{% endtabs %}

### `DATE_FORMAT`

Default date format.

{% tabs %}
{% tab title="Possible Values" %}
See the [PHP \`date\` format documentation](https://www.php.net/manual/en/function.date.php#refsect1-function.date-parameters) for possible values.
{% endtab %}

{% tab title="Default Value" %}
`Y-m-d H:i:s`
{% endtab %}
{% endtabs %}

### `FEATURED_IMAGES`

Controls the vertical sizing of featured post images.

{% tabs %}
{% tab title="Possible Values" %}
* `full` - Display the full height images
* `collapsed` - Constrain the images height&#x20;
{% endtab %}

{% tab title="Default Value" %}
`collapsed`
{% endtab %}
{% endtabs %}

### `META_DESCRIPTION`

[Meta tag](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/meta) description text.

{% tabs %}
{% tab title="Possible Values" %}
Any string.
{% endtab %}

{% tab title="Default Value" %}
`Yet another amazing blog, published with Plume.`
{% endtab %}
{% endtabs %}

### `PAGINATION`

Whether or not post pagination is enabled.

{% tabs %}
{% tab title="Possible Values" %}
`true` or `false`
{% endtab %}

{% tab title="Default Value" %}
`true`
{% endtab %}
{% endtabs %}

### `POSTS_PER_PAGE`

The number of posts to display per page when pagination is enabled.

{% tabs %}
{% tab title="Possible Values" %}
Any positive integer.
{% endtab %}

{% tab title="Default Value" %}
`10`
{% endtab %}
{% endtabs %}

### `SHIKI_THEME_ID`

The Shiki theme ID used for syntax highlighting in rendered code blocks.

{% tabs %}
{% tab title="Possible Values" %}
See the [Shiki themes documentation](https://shiki.style/themes) for a list of themes to choose from.
{% endtab %}

{% tab title="Default Value" %}
`catppuccin-frappe`
{% endtab %}
{% endtabs %}

### `SITE_TITLE`

The title of your blog. This will be displayed in the browser tab/title bar along with the current path.

{% tabs %}
{% tab title="Possible Values" %}
Any string.
{% endtab %}

{% tab title="Default Value" %}
Yet another amazing blog
{% endtab %}
{% endtabs %}

### `TAGS_LINK`

Whether or not to show the "Tags" navigation link when there are multiple unique post tags.

{% tabs %}
{% tab title="Possible Values" %}
`true` or `false`
{% endtab %}

{% tab title="Default Value" %}
`true`
{% endtab %}
{% endtabs %}

### `TIMEZONE`

Timezone used for date formatting.

{% tabs %}
{% tab title="Possible Values" %}
See the [PHP List of Supported Timezones](https://www.php.net/manual/en/timezones.php) for a list of supported values.
{% endtab %}

{% tab title="Default Value" %}
The server's timezone.
{% endtab %}
{% endtabs %}

### `USAGE_REPORTING`

Enable usage reporting. The data collected contains aggregated metrics and does not identify individual users.

{% tabs %}
{% tab title="Possible Values" %}
`true` or `false`
{% endtab %}

{% tab title="Default Value" %}
`true`
{% endtab %}
{% endtabs %}
