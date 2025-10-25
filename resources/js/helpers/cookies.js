export default {
    set(key, value, path = '/') {
        document.cookie = `${key}=${value};path=${path}`;
    },

    get(key, flags) {
        const [, value] = document.cookie.match(getRegExp(key, flags)) ?? [null, null];

        return decodeURIComponent(value);
    },

    has(key, flags) {
        return getRegExp(key, flags).test(document.cookie);
    },
};

function getRegExp(key, flags) {
    return new RegExp(`${key}=(.+?)(?:;|$)`, flags);
}
