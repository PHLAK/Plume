import { defineConfig } from 'vitepress'

const analyticsScript = process.env.ENVIRONMENT === 'production' ? [ 'script', {
    defer: '',
    src: 'https://analytics.phlak.net/script.js',
    'data-website-id': '07fa46f2-8a0d-4cc0-b3aa-c7bf9e1b6be7',
}] : [];

export default defineConfig({
    title: 'Plume Documentation',
    description: 'The official Plume documentation',

    head: [
        ['link', { rel: 'icon', href: '/images/plume.svg' }],
        ...analyticsScript,
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
                    { text: 'Configuration Overview', link: '/configuration/configuration-overview' },
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
            { icon: 'bluesky', link: 'https://bsky.app/profile/plume.pub' },
            { icon: 'github', link: 'https://github.com/PHLAK/Plume' },
        ],

        editLink: {
            pattern: 'https://github.com/PHLAK/Plume/edit/master/docs/:path'
        },

        lastUpdated: true,
    },
});
