---
icon: screwdriver-wrench
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

# Advanced Configuration

Some configuration values do not have corresponding environment variable. These values can only be controlled through their entries in the `app/config` files.

{% hint style="warning" %}
When upgrading your application the files in `app/config` will be overwritten. If you make manual changes to these files be sure to back up any modifications before upgrading.
{% endhint %}

The application configs are broken up into separate files based on their use and give you full control over every application option and even allows full PHP code if desired.

{% hint style="info" %}
Plume utilizes PHP-DI for it's configuration. See the [PHP Definitions documentation](https://php-di.org/doc/php-definitions.html) for more info on writing manual configuration definitions.
{% endhint %}

For additional information about individual configuration options reference the individual config documentation.

{% hint style="success" icon="clock-five" %}
More coming soon...
{% endhint %}
