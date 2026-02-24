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
     * The application language.
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
     * Whether or not post pagination is enabled.
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
     * Controls the vertical sizing of featured post images. Avaialable options:
     *
     *   'full' - Display the full height images
     *   'collapsed' - Constrain the images height
     *
     * Default value: 'collapsed'
     */
    'featured_images' => env('FEATURED_IMAGES', 'collapsed'),

    /**
     * Whether or not to show the "Tags" navigation link when there are
     * multiple unique post tags.
     *
     * Default value: true
     */
    'tags_link' => env('TAGS_LINK', true),

    /**
     * Whether or not to show the "Authors" navigation link when there are
     * multipe unique post authors.
     *
     * Default value: false
     */
    'authors_link' => env('AUTHORS_LINK', false),

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
     * Timezone used for date formatting. For a list of supported timezones see:
     * https://www.php.net/manual/en/timezones.php.
     *
     * Default value: The server's timezone
     */
    'timezone' => env('TIMEZONE', date_default_timezone_get()),

    /**
     * Enable usage reporting. The data collected contains aggregated metrics
     * and does not identify individual users.
     *
     * Default value: true
     */
    'usage_reporting' => env('USAGE_REPORTING', true),

];
