import axios from 'axios';

export default () => ({
    visible: false,
    query: '',
    results: [],
    loading: false,

    show() {
        this.visible = true;
    },

    hide() {
        this.visible = false;
        this.query = '';
        this.results = [];
    },

    async search() {
        this.loading = true;

        if (this.query === '') {
            this.results = [];
            this.loading = false;

            return;
        }

        let response;

        try {
            response = await axios.get('/search', { params: { q: this.query } });
        } catch (error) {
            console.error(error);
        } finally {
            this.loading = false;
        }

        this.results = response.data;
    },
});
