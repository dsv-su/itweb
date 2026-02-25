const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    darkMode: 'class',
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './app/**/*.php',
        './node_modules/flowbite/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', 'Inter', 'Helvetica', 'Arial', 'sans-serif'],
                serif: ['Merriweather', 'Georgia', 'Cambria', 'Times New Roman', 'serif'],
                mono: ['Courier New', 'monospace'],
                sudepartment: ['TheSansB2-Light', 'Verdana', 'sans-serif'],
                rock: ['Rock Salt'],
            },
            colors: {
                suprimary: '#002f5f',
                sudepartment: '#33587f',
                susecondary: '#acdee6',
            },
        },
    },
    plugins: [
        require('flowbite/plugin'),
    ],
};

