const colors = require('tailwindcss/colors')

module.exports = {
    content: [
        './resources/**/*.blade.php', 
        './vendor/filament/**/*.blade.php',
        './vendor/savannabits/filament-flatpickr/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                danger: colors.rose,
                primary: colors.blue,
                success: colors.green,
                warning: colors.yellow,
            },
        },
        fontFamily: {
            sans: ['"Tajawal"', 'sans-serif'],
        }
    },
    plugins: [
        require('@tailwindcss/forms'), 
        require('@tailwindcss/typography'), 
    ],
    // 'google_fonts' => "https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap",
}
