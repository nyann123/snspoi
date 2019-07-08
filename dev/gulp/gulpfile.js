// gulpプラグインの読み込み
const gulp = require("gulp");
const sass = require("gulp-sass");
const cleanCSS = require('gulp-clean-css'); //css圧縮
const uglify = require('gulp-uglify'); //js圧縮

//js圧縮
gulp.task('js', function(done) {
    gulp.src("../resource/js/*.js")
    .pipe(uglify())
    .pipe(gulp.dest("../../heroku/resource/js"));
    done();
});

//scss
gulp.task('scss', function (done) {
    gulp.src("../resource/css/scss/*.scss")
    .pipe(sass({outputStyle: "expanded"})
    .on("error", sass.logError))
    .pipe(gulp.dest("../resource/css"))
    .pipe(gulp.dest("../../heroku/resource/css"))
    done();
});

gulp.task('php', function(done) {
    gulp.src(["../resource/*.php","!../resource/db_connect.php"])
    .pipe(gulp.dest("../../heroku/resource"));
    done();
});

gulp.task('watch',function(){
  gulp.watch("../resource/css/scss", gulp.task('scss'));
  gulp.watch("../resource/js",  gulp.task('js'));
  gulp.watch('../resource/*.php', gulp.task('php'));
});

gulp.task("default",gulp.task('watch'));
