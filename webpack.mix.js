const mix = require("laravel-mix");
const path = require("path");

mix.alias({
  "@": path.join(__dirname, "resources/js")
})

mix
  .js("resources/js/app.js", "public/js")
  .vue()
  .sass("resources/sass/app.scss", "public/css");
