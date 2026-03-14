# Advanced Configuration

Some configuration values do not have corresponding environment variable. These
values can only be controlled through their entries in the `app/config` files.

> [!DANGER]
> When upgrading your application the files in `app/config` will be overwritten.
> If you make manual changes to these files be sure to back up any modifications
> before upgrading.

The application configs are broken up into separate files based on their use and
give you full control over every application option and even allows full PHP
code if desired.

> [!TIP]
> Plume utilizes [PHP-DI](https://php-di.org) for it's configuration. See the
> [PHP Definitions documentation](https://php-di.org/doc/php-definitions.html)
> for more info on writing manual configuration definitions.

For additional information about individual configuration options reference the
individual config documentation.

## Caching

> [!INFO] Coming soon...
