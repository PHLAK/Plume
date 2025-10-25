import { createApp, computed, ref } from 'vue'
import cookies from '@/helpers/cookies.js';

const app = createApp({
    setup() {
        const theme = ref('light');

        return { theme };
    },

    methods: {
        toggleTheme() {
            this.theme = this.theme === 'light' ? 'dark' : 'light';
        }
    },

    mounted() {
        this.theme = cookies.get('theme');
    },

    watch: {
        theme (value, previos) {
            cookies.set('theme', value);
        }
    }
});

app.mount('#app');
