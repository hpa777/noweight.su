const mix = require('laravel-mix');
require('mix-html-builder');
require('laravel-mix-clean');
require('laravel-mix-svg-sprite');

const buildPath = "./app"; 
const srcPath = "./src";

mix.setPublicPath(buildPath)
	.html({
		htmlRoot: srcPath + '/*.html', // Your html root file(s) 
		output: '', // The html output folder
		partialRoot: srcPath + '/partials',    // default partial path
		layoutRoot: srcPath + '/layouts',    // default partial path
		inject: true,		
		minify: {
			removeComments: true
		}		
	})
	.svgSprite(
		srcPath + '/img/icons', // The directory containing your SVG files
		'/images/sprite.svg', // The output path for the sprite	
		undefined,
		{
			pluginOptions: {
				plainSprite: true,
				spriteAttrs: {
					id: 'my-custom-sprite-id'
				}
			} 
		}
	)
	.copyDirectory(srcPath + '/scss/fonts', buildPath + '/fonts')
	/*
	.copyDirectory('node_modules/slick-carousel/slick/fonts', buildPath + '/css/fonts')
	.copy('node_modules/slick-carousel/slick/ajax-loader.gif', buildPath + '/css/ajax-loader.gif')	
	*/
	.sass(srcPath + '/scss/app.scss', '/css/app.css')
	/*
	.options({
		processCssUrls: false
	})
	*/
	.js(srcPath + '/js/app.js', 'js/app.js')
	.extract([
		'jquery',
		'slick-carousel'
		//'jquery-ui'
	])
	.autoload({
		jquery: ['$', 'window.jQuery', "jQuery", "window.$", "jquery", "window.jquery"]
	})
	.clean()
	.browserSync({
		watch: true,
		server: buildPath,
		files: [
			buildPath + "/css/!*.css",
			buildPath + "/js/!*.js",
			buildPath + "/!*.html"
		]
	});
if (!mix.inProduction()) {
	mix.sourceMaps().webpackConfig({
		devtool: 'source-map'
	});
} 

/*
require('mix-html-builder');
//require('mix-tailwindcss');
require('laravel-mix-clean');
require('laravel-mix-clean-css');
//require('laravel-mix-critical');
const path = require('path');
const buildPath = "./app";
const srcPath = "./src";

mix.setPublicPath(buildPath)
	.setResourceRoot('../') // Turns assets paths in css relative to css file
	.sass(srcPath + '/sass/app.scss', 'app.css')		
	.tailwind()
	.js(srcPath + '/app.js', 'js/app.js')
	//.vue()
	
	.critical({
        enabled: mix.inProduction(),
        urls: [
            { src: 'http://rucar.su/', dest: 'public/css/index_critical.min.css' },
        ],
        options: {
            minify: true,
        },
    })
	
	.extract([
		'jquery',
		'alpinejs'
	])
	// if use jQuery
	.autoload({
		jquery: ['$', 'window.jQuery', "jQuery", "window.$", "jquery", "window.jquery"]
	})
	// disable if not use html
	.html({
		htmlRoot: srcPath + '/index.html', // Your html root file(s)
		output: '', // The html output folder
		partialRoot: srcPath + '/partials',    // default partial path
		layoutRoot: srcPath + 'layouts',    // default partial path
		inject: true,
		minify: {
			removeComments: true
		}
	})
	.webpackConfig({
		output: {
			publicPath: '.'
		}
	})
	.options({
		terser: {
			extractComments: false,
		}
	})
	/*.browserSync({
		watch: true,
		server: buildPath,
		files: [
			buildPath + "/css/!*.css",
			buildPath + "/js/!*.js",
			buildPath + "/!*.html"
		]
	})
	.cleanCss({
		level: 2,
		format: mix.inProduction() ? false : 'beautify' // Beautify only in dev mode
	})
	



if (mix.inProduction()) {
	mix.version();
} else {
	mix.sourceMaps().webpackConfig({
		devtool: 'source-map'
	});
} 
*/