// postcss.config.cjs

module.exports = {
  plugins: {
    'postcss-import': {}, // اگر نصب شده باشد
    '@tailwindcss/postcss': {}, // پلاگین صحیح برای نسخه جدید
    autoprefixer: {},
  },
};