// tailwind.config.cjs

/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php", 
    "./resources/**/*.js",
    "./resources/**/*.vue",
    
    // **این خط جدید را اضافه کنید:**
    "./node_modules/flowbite/**/*.js" 
  ],
  theme: {
    extend: {},
  },
  // **این خط جدید را اضافه کنید:**
  plugins: [
    require('flowbite/plugin')
  ],
}