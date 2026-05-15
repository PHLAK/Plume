export default () => ({
    query: null,
    visible: false,

    show() {
        this.visible = true;
    },

    hide() {
        this.visible = false;
    },

    submit() {
        window.location.href=`/search?q=${this.query}`;
    },
});
