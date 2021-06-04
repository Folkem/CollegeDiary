module.exports = {
    purge: [],
    darkMode: false, // or 'media' or 'class'
    theme: {
        extend: {
            fontFamily: {
                'bitter-italic': ['"Bither-Italic"', 'sans-serif'],
                'gotham-pro': ['GothamPro', 'sans-serif'],
                'gotham-pro-bold': ['"GothamPro-Bold"', 'sans-serif'],
                'museo-cyrl': ['"Museo Cyrl"', 'sans-serif'],
            },
            borderWidth: {
                DEFAULT: '1px',
                '0': '0',
                '2': '2px',
                '3': '3px',
                '4': '4px',
                '5': '5px',
                '6': '6px',
                '7': '7px',
                '8': '8px',
            },
            maxWidth: {
                '1200': '1200px',
            },
            height: {
                '500': '520px',
                '820': '820px',
                'fit': 'fit-content',
                'min': 'min-content',
            },
            width: {
                '400': '400px',
                '1200': '1200px',
            },
        },
    },
    variants: {
        extend: {},
    },
    plugins: [
        require("tailwindcss-debug-screens"),
        require('tailwind-hamburgers'),
    ],
}
