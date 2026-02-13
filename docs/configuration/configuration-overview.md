---
icon: square-sliders
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

# Configuration Overview

Plume allows customization through configuration. You can configure Plume in a few different ways. The simplest and _recommended_ way to configure Plume is through environment variables.

### The `.env` File

Most configuration is possible via the `.env` file. You may define environment variables and their value in this file.

{% hint style="success" %}
This is the recommended method for configuring your app.
{% endhint %}

To get started:

1. Copy `.env.example` to `.env`
2. Edit the configuration values in `.env`

<pre><code>/path/to/plume
├── data
│   └── [your data]
├── docker-compose.yaml
├── <a data-footnote-ref href="#user-content-fn-1">.env</a>
└── <a data-footnote-ref href="#user-content-fn-2">.env.example</a>
</code></pre>

The default `.env` file should look something like this:

{% code title=".env" %}
```dotenv
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
{% endcode %}

{% hint style="info" %}
You can find a list of environment variables and their function in the [Environment Variables](environment-variables.md) documentation.
{% endhint %}

### User Customization

{% hint style="warning" %}
**Advanced user customization is currently in development.**

If there's something you would like to be able to customize that isn't currently possible, open a feature request.
{% endhint %}

### Custom JavaScript and CSS

Arbitrary code like CSS & JavaScript may be included in the HTML output of your site through the `customizations` file injection. This is particularly useful for including analytics tracking code from [Google Analytics](https://analytics.google.com), [Matomo Analytics](https://matomo.org), [Umami Analytics](https://umami.is) or other similar analytics service.

To inject these into your page, create a file named `customizations` in the data directory and place your code into this file.

<pre><code>/path/to/plume
├── data
│   ├── [other files and folders]
│   └── <a data-footnote-ref href="#user-content-fn-3">customizations</a>
└── docker-compose.yaml
</code></pre>

{% code title="customizations" %}
```html
<!-- Put your custom code here -->
```
{% endcode %}

### Caching

{% hint style="success" %}
Coming soon...
{% endhint %}

[^1]: Edit this file

[^2]: Copy this file

[^3]: Create this file
