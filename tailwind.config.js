import defaultTheme from 'tailwindcss/defaultTheme'

export default {
  content: [
    './assets/src/**/*.{vue,js,css, php}'
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
