/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.php",
    "!./assets/**",
    "!./3.4.17/**",
    "!./node_modules/**",
  ],
  theme: {
    extend: {
      fontFamily: { sans: ['Vazirmatn', 'sans-serif'] }
    }
  },
  plugins: [],
};
