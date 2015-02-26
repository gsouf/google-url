var gulp = require('gulp'),
    sass = require('gulp-sass'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    ngAnnotate = require('gulp-ng-annotate'),
    minifyCSS = require('gulp-minify-css'),
    translate = require('gulp-translator'),
    rename = require("gulp-rename"),
    sourcemaps = require('gulp-sourcemaps');


var cssPath = [
    './src/css/foundation.css',
    './src/css/main.css',
    './src/css/ligature.css',
    './src/css/prettify.css',
    './src/css/app.css'
];

gulp.task('css', function () {
    gulp.src(cssPath)
        .pipe(sourcemaps.init())
        .pipe(minifyCSS({keepBreaks:true}))
        .pipe(concat('app.css'))
        .pipe(sourcemaps.write("./"))
        .pipe(gulp.dest('./dist/'))
});

var jsPath = [
    './src/js/angular.js',
    './src/js/angular-route.js',
    './src/js/jquery.js',
    './src/js/jquery.cookie.js',
    './src/js/modernizr.js',
    './src/js/foundation.js',
    './src/js/foundation.tooltip.js',
    './src/js/foundation.topbar.js',

    './src/js/prettify.js',

    './src/js/FaceS.js',
    './src/js/app.js'
];

gulp.task('js', function() {
    gulp.src(jsPath)
        .pipe(sourcemaps.init())
        .pipe(ngAnnotate())
        .pipe(uglify())
        .pipe(concat('app.js'))
        .pipe(sourcemaps.write("./"))
        .pipe(gulp.dest('./dist/'))
});


gulp.task('translate', function() {
    var translations = ['en'];

    translations.forEach(function(translation){
        gulp.src('./src/pages/**/*.html')
            .pipe(
            translate('./src/locales/'+ translation +'.yml')
                .on('error', function(){
                    console.log("lang : " + translation);
                    console.dir(arguments);
                })
        )
            .pipe(gulp.dest('./dist/langs/' + translation));

        gulp.src('./dist/langs/' + translation + '/index.html')
            .pipe( rename( (translation == "en" ? "index" : translation) + ".html") )
            .pipe( gulp.dest("./") );
    });



});


gulp.task('default', ['css', 'js','translate']);