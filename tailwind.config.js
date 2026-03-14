import defaultTheme from 'tailwindcss/defaultTheme'

export default {
  content: [
    './assets/src/**/*.{vue,js,css}'
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
