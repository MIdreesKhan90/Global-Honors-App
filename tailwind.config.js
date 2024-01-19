import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                "dmSerif-display": "'DM Serif Display', serif",
                "mulish": "'Mulish', sans-serif"
            },
            colors: {
                "primary": {
                    50: "#D7E8EA",
                    100: "#BDD9DB",
                    200: "#95C3C6",
                    600: "#3F7477",
                    700: "#2C5558",
                    800: "#234648",
                    900: "#193638",
                },
                "secondary": {
                    300: "#C3B041"
                },
                "error": {
                    500: "#F04438",
                    700: "#B42318"
                },
                "lime": {
                    700: "#CBEF43"
                },
            },
            height: {
                '92vh': '92vh',
                'body': 'calc(100vh - 133px)',
            },
            minHeight: {
                '180': '180px',
                'body': 'calc(100vh - 133px)',
            },
        },
    },

    plugins: [forms],
};
