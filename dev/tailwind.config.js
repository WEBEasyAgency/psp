/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './index.html',
    './src/**/*.{vue,js,ts,jsx,tsx}',
  ],
  corePlugins: {
    container: false, // Отключаем .container из Tailwind
    preflight: false, // Отключаем Preflight (используем reset из layout/css/libs.min.css)
  },
}
