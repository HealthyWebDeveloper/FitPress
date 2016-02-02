// https://travismaynard.com/writing/getting-started-with-gulp
// https://www.npmjs.com/package/gulp-minify-css

// npm install jshint gulp-jshint gulp-sass gulp-concat gulp-uglify gulp-rename gulp-minify-css --save-dev 

// Include gulp
var gulp = require('gulp'); 

// Include Our Plugins
var jshint = require('gulp-jshint');
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var minify = require('gulp-minify-css');

// Lint Task
gulp.task('lint', function() {
    return gulp.src('src/js/*.js')
        .pipe(jshint())
        .pipe(jshint.reporter('default'));
});

// Compile Our Sass
gulp.task('sass-backend', function() {
    return gulp.src([ 
                        'src/scss/backend/*.scss', 
                        'src/scss/fitpress-core-styles.scss' 
                    ], {base: 'src/scss/'} )
        .pipe(sass())
        .pipe(concat('fitpress-backend-styles.css'))
        .pipe(gulp.dest('assets/css'))
        .pipe(minify())
        .pipe(rename('fitpress-backend-styles.min.css'))
        .pipe(gulp.dest('assets/css'));
});

// Compile Our Sass
gulp.task('sass-frontend', function() {
    return gulp.src([ 
                        'src/scss/frontend/*.scss', 
                        'src/scss/fitpress-core-styles.scss'
                    ], {base: 'src/scss/'} )
        .pipe(sass())
        .pipe(concat('fitpress-frontend-styles.css'))
        .pipe(gulp.dest('assets/css'))
        .pipe(minify())
        .pipe(rename('fitpress-frontend-styles.min.css'))
        .pipe(gulp.dest('assets/css'));
});


// Concatenate & Minify JS
gulp.task('scripts', function() {
    return gulp.src('src/js/*.js')
        .pipe(concat('fitpress-scripts.js'))
        .pipe(gulp.dest('assets/js'))
        .pipe(rename('fitpress-scripts.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('assets/js'));
});

// Watch Files For Changes
gulp.task('watch', function() {
    gulp.watch('src/js/*.js', ['lint', 'scripts']);
    gulp.watch('src/scss/*.scss', ['sass-backend', 'sass-frontend']);
    gulp.watch('src/scss/backend/*.scss', ['sass-backend']);
    gulp.watch('src/scss/frontend/*.scss', ['sass-frontend']);
});

// Default Task
gulp.task('default', ['lint', 'sass-backend', 'sass-frontend', 'scripts', 'watch']);