module.exports = {
    title: 'Rich Variables Plugin Documentation',
    description: 'Documentation for the Rich Variables plugin',
    base: '/docs/rich-variables/',
    lang: 'en-US',
    head: [
        ['meta', {content: 'https://github.com/nystudio107', property: 'og:see_also',}],
        ['meta', {content: 'https://twitter.com/nystudio107', property: 'og:see_also',}],
        ['meta', {content: 'https://youtube.com/nystudio107', property: 'og:see_also',}],
        ['meta', {content: 'https://www.facebook.com/newyorkstudio107', property: 'og:see_also',}],
    ],
    themeConfig: {
        repo: 'nystudio107/craft-richvariables',
        docsDir: 'docs/docs',
        docsBranch: 'develop',
        algolia: {
            appId: '',
            apiKey: '',
            indexName: 'rich-variables'
        },
        editLinks: true,
        editLinkText: 'Edit this page on GitHub',
        lastUpdated: 'Last Updated',
        sidebar: 'auto',
    },
};
