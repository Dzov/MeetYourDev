var gulp = require('gulp');

gulp.task('default', function () {});

var sass = sass = require('gulp-sass');

gulp.task('sass', function () {
    gulp.src('./src/MYD/PlatformBundle/Resources/public/scss/*.scss')
        .pipe(sass({sourceComments: 'map', errLogToConsole: true}))
        .pipe(gulp.dest('./web/bundles/mydplatform/css/'
    ));
});

gulp.task('watch', function () {
    var onChange = function (event) {
        console.log('File '+event.path+' has been '+event.type);
    };
    gulp.watch('./src/MYD/PlatformBundle/Resources/public/scss/*.scss', ['sass'])
        .on('change', onChange);
});


var exec = require('child_process').exec;

gulp.task('installAssets', function () {
    exec('php bin/console assets:install', logStdOutAndErr);
});

// Without this function exec() will not show any output
var logStdOutAndErr = function (err, stdout, stderr) {
    console.log(stdout + stderr);
};