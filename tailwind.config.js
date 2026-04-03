import defaultTheme from 'tailwindcss/defaultTheme'

export default {
  content: [
    './admin/**/*.{php}',
    './core-ui/**/*.{php}',
    './assets/src/**/*.{vue,js,css,jsx}'
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
