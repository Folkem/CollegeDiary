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
            minWidth: {
                'min': 'min-content',
            },
            maxWidth: {
                '1200': '1200px',
            },
            height: {
                '500': '500px',
                '820': '820px',
                'fit': 'fit-content',
                'min': 'min-content',
            },
            width: {
                '400': '400px',
                '500': '500px',
                '600': '600px',
                '800': '800px',
                '1000': '1000px',
                '1200': '1200px',
                '1/10': '10%',
                '2/10': '20%',
                '3/10': '30%',
                '4/10': '40%',
                '5/10': '50%',
                '6/10': '60%',
                '7/10': '70%',
                '8/10': '80%',
                '9/10': '90%',
            },
        },
    },
    variants: {
        extend: {
            textColor: ['visited']
        },
    },
    plugins: [
        require("tailwindcss-debug-screens"),
        require('tailwind-hamburgers'),
    ],
}
