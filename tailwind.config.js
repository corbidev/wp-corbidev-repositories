import defaultTheme from 'tailwindcss/defaultTheme'

export default {
  content: [
    './admin/**/*.php',
    './assets/src/**/*.{js,ts,jsx,tsx,css}'
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['inherit', ...defaultTheme.fontFamily.sans],
      }
    }
  },
  corePlugins: {
    preflight: false
  }
}
