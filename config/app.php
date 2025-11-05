<?php

declare(strict_types=1);

use function DI\env;

return [

    /**
     * Enable application debugging and display error messages.
     *
     * !!! WARNING !!!
     * It is recommended that debug remains OFF unless troubleshooting an issue.
     * Leaving this enabled WILL cause leakage of sensitive server information.
     *
     * Default value: false
     */
    'debug' => env('APP_DEBUG', false),

    /**
     * The application interface language.
     *
     * Possible values: See the 'translations' folder for available translations.
     *
     * Defualt value: 'en'
     */
    'language' => env('APP_LANGUAGE', 'en'),

    /**
     * The title of your blog. This will be displayed in the browser tab/title
     * bar along with the current path.
     *
     * Default value: 'Yet another amazing blog'
     */
    'site_title' => env('SITE_TITLE', 'Yet another amazing blog'),

    /**
     * Meta tag description text.
     *
     * Default value: 'Yet another amazing blog, published with Plume.'.
     */
    'meta_description' => env('META_DESCRIPTION', 'Yet another amazing blog, published with Plume.'),

    /**
     * Post pagination.
     *
     * Default value: true.
     */
    'pagination' => env('PAGINATION', true),

    /**
     * The number of posts to display per page when pagination is enabled.
     *
     * Default value: 10
     */
    'posts_per_page' => env('POSTS_PER_PAGE', 10),

    /**
     * Enables the "Tags" page (and navigation link) when the number of unique
     * post tags equals or exceeds this value.
     *
     * Default value: 3
     */
    'tags_threshold' => env('TAGS_THRESHOLD', 3),

    /**
     * Enables the "Authors" page (and navigation link) when the number of
     * unique post authors equals or exceeds this value.
     *
     * Default value: 2
     */
    'authors_threshold' => env('AUTHORS_THRESHOLD', 2),

    /**
     * Default date format. For additional info on date formatting see:
     * https://www.php.net/manual/en/function.date.php.
     *
     * Default value: 'Y-m-d H:i:s'
     */
    'date_format' => env('DATE_FORMAT', 'Y-m-d H:i:s'),

    /**
     * The Shiki theme ID used for syntax highlighting in rendered code blocks.
     * See https://shiki.style/themes for a list of themes to choose from.
     *
     * Default value: 'catppuccin-frappe'
     */
    'shiki_theme_id' => env('SHIKI_THEME_ID', 'catppuccin-frappe'),

    /**
     * Commonmark configuration array. Controls the way markdown is rendered.
     *
     * Default value: <preconfigured array>
     */
    'commonmark_config' => [
        'alert' => [
            'icons' => [
                'active' => true,
                'use_svg' => true,
            ],
        ],
    ],

    /**
     * Timezone used for date formatting. For a list of supported timezones see:
     * https://www.php.net/manual/en/timezones.php.
     *
     * Default value: The server's timezone
     */
    'timezone' => env('TIMEZONE', date_default_timezone_get()),

];
