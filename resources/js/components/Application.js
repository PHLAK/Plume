export default () => ({
    systemTheme: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light',

    themes: ['system', 'dark', 'light'],

    init() {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (darkMode) => {
            this.systemTheme = darkMode.matches ? 'dark' : 'light';
        });

        const [cookie, theme] = document.cookie.match(/theme=(dark|light|system)/) || [null, 'system'];

        this.$store.theme = theme;

        if (cookie === undefined) {
            this.storeThemeCookie(this.$store.theme);
        }
    },

    toggleTheme() {
        this.$store.theme = this.themes[this.themes.indexOf(this.$store.theme) + 1] ?? this.themes[0];
    },

    storeThemeCookie(theme) {
        document.cookie = `theme=${theme}; expires=Fri, 31 Dec 9999 23:59:59 GMT; path=/; SameSite=Lax`;
    }
});
