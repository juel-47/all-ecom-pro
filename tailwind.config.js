/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.jsx",
        "./resources/**/*.vue",
    ],
    theme: {
        container: {
            center: true,
            screens: {
                xs: "100%",
                sm: "540px",
                md: "720px",
                lg: "960px",
                xl: "1140px",
                "2xl": "1750px",
                // "3xl": "1750px", // প্রয়োজন হলে আনকমেন্ট করো
            },
        },
        extend: {
            screens: {
                xs: "100%",
                sm: "576px",
                md: "768px",
                lg: "992px",
                xl: "1200px",
                "2xl": "1400px",
                "3xl": "1700px",
            },
            fontFamily: {
                manrope: ['"Manrope"', "sans-serif"],
                vidaloka: ['"Vidaloka"', "serif"],
                story: ['"Story Script"', "sans-serif"],
                curs: ['"Monsieur La Doulaise"', "cursive"],
                michroma: ['"Michroma"', "sans-serif"],
                mont: ['"Montserrat"', "sans-serif"],
            },
            colors: {
                red: "#e65237",
                yellow: "#ffdd55",
                dark1: "#37352b",
                dark2: "#28261e",
                cream: "#fffbed",
                gray: "#ded8c6",
            },
        },
    },
    plugins: [],
};
