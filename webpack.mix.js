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
   //.js('resources/assets/js/moment.js', 'public/js')
   //.js('resources/assets/js/daterangepicker.js', 'public/js')
   .js('resources/assets/js/fileinput.js', 'public/js')
   .js('resources/assets/js/fileinput_zh.js', 'public/js')

   .sass('resources/assets/sass/daterangepicker.scss', 'public/css')
   .sass('resources/assets/sass/fileinput.scss', 'public/css')
   .sass('resources/assets/sass/app.scss', 'public/css');

mix.copy('resources/assets/imgs/nav_bg.png', 'public/imgs');
mix.copy('resources/assets/imgs/up_pic_bg.jpg', 'public/imgs');
mix.copy('resources/assets/imgs/headimg.jpg', 'public/imgs');
mix.copy('resources/assets/imgs/qrcode.jpg', 'public/imgs');
mix.copy('resources/assets/imgs/loading.gif', 'public/imgs');
mix.copy('resources/assets/imgs/loading-sm.gif', 'public/imgs');

//mix.copy('resources/assets/css/daterangepicker-bs3.css', 'public/css');
mix.copy('resources/assets/js/moment.js', 'public/js');
mix.copy('resources/assets/js/daterangepicker.js', 'public/js');


mix.browserSync({
    proxy: 'local.tyoupub.com/lar/public/admin',   // apache或iis等代理地址
    port: 80,
    notify: false,        // 刷新是否提示
    watchTask: true,
    open: 'external',
    host: 'local.tyoupub.com',  // 本机ip, 这样其他设备才可实时看到更新
});
