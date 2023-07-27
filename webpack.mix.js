const mix = require('laravel-mix');

mix.autoload({
    'jquery': ['$', 'window.jQuery', 'jQuery'],
});

mix.webpackConfig({
    output: {
        chunkFilename: mix.inProduction() ? "js/prod/chunks/[name]?id=[chunkhash].js" : "js/dev/chunks/[name].js"
    }
});
mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');


if (mix.inProduction()) {
    mix.version();
}
