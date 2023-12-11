/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {},
    colors: {
      'white': '#FFFFFF',
      'primary': '#0056B8',
      'bg-color': '#EEF4FB',
      'dark-color': '#20271F',
      'header-table': '#F3F3F3',
      'font-color': '#343841',
      'green-color': '#5BA200',
      'yellow-color': '#F3C96B',
      'red-color': '#D0112B',
      'blue-color': '#5971C0',
      'choco-color': '#AB5200',
      'cyan-color': '#148EA9',
      'purple-color': '#7F2BB3',
      'pink-color': '#E82A9C',
      'salmon-color': '#DE6E6A',
      'cream-color': '#F3AC8D'
    },
  },
  plugins: [],
}

