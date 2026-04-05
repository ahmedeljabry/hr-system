export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Outfit', 'Inter', 'ui-sans-serif', 'system-ui'],
            },
            colors: {
                primary: 'hsl(var(--primary))',
                surface: 'hsl(var(--surface))',
                'text-main': 'hsl(var(--text-main))',
            }
        },
    },
    plugins: [],
}
