import { defineConfig } from 'vitepress'

export default defineConfig({
    title: 'Plume Documentation',
    description: 'The official Plume documentation',

    head: [
        ['link', { rel: 'icon', href: '/images/plume.svg' }],
        ['script', { src: 'https://example.com/analytics.js' }],
    ],

    themeConfig: {
        logo: '/images/plume.svg',

        nav: [
          { text: 'Documentation', link: '/' },
          { text: 'CLI Reference', link: '/cli-reference' }
        ],

        sidebar: [
            {
                items: [
                    { text: 'Introduction', link: '/' },
                    { text: 'Installation', link: '/installation' },
                ]
            },
            {
                text: 'Configuration',
                items: [
                    { text: 'Configuration Overview', link: '/configuration/configuration-overview', activeMatch: '/configuration/' },
                    { text: 'Environment Variables', link: '/configuration/environment-variables' },
                    { text: 'Advanced Configuration', link: '/configuration/advanced-configuration' },
                ]
            },
            {
                text: 'Publishing',
                items: [
                    { text: 'Posts', link: '/publishing/posts' },
                    { text: 'Pages', link: '/publishing/pages' },
                    { text: 'Markdown', link: '/publishing/markdown' },
                ]
            },
            {
                text: 'Help & Support',
                items: [
                    { text: 'Troubleshooting', link: '/help-and-support/troubleshooting' },
                ]
            },
        ],

        outline: { level: [2, 4] },

        search: { provider: 'local' },

        socialLinks: [
            { icon: 'github', link: 'https://github.com/PHLAK/Plume' }
        ],

        editLink: {
            pattern: 'https://github.com/PHLAK/Plume/edit/master/docs/:path'
        },

        lastUpdated: true,
    },
});
