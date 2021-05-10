module.exports = {
    purge: ["./src/**/*.html", "./src/**/*.vue", "./src/**/*.ts"],
    darkMode: false, // or 'media' or 'class'
    theme: {
        extend: {},
        backgroundColor: (theme) => ({
            ...theme("colors"),
            header: "#016379",
            button: "rgba(59, 130, 246, var(--tw-bg-opacity))",
        }),
    },
    variants: {
        extend: {
            backgroundColor: ["even"],
        },
    },
    plugins: [],
};
