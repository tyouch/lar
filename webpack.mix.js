const { mix } = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

/*mix.webpackConfig({
    resolve: {
        modules: [
            path.resolve(__dirname, 'vendor/laravel/spark/resources/assets/js')
        ]
    }
});*/

mix.js('resources/assets/js/app.js', 'public/js').extract(['vue'])
   .js('resources/assets/js/bootstrap-treeview.min.js', 'public/js')
   //.scripts(['resources/assets/js/echarts.min.js'], 'public/js')
   //.babel(['resources/assets/js/echarts.min.js'], 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');

mix.copy('resources/assets/imgs/nav_bg.png', 'public/imgs');
mix.copy('resources/assets/imgs/up_pic_bg.jpg', 'public/imgs');


mix.browserSync({
    proxy: 'local.tyoupub.com/lar/public',   // apache或iis等代理地址
    port: 80,
    notify: false,        // 刷新是否提示
    watchTask: true,
    open: 'external',
    host: 'local.tyoupub.com',  // 本机ip, 这样其他设备才可实时看到更新
});
