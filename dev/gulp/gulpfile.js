// gulpプラグインの読み込み
const gulp = require("gulp");
const sass = require("gulp-sass");  //sassをコンパイル
const rename = require('gulp-rename');  //ファイルリネーム
const autoprefixer = require('gulp-autoprefixer');  //ベンダープレフィックスを付与
const sourcemaps = require('gulp-sourcemaps');  //sourcemapsの付与
const cleanCSS = require('gulp-clean-css'); //css圧縮
const uglify = require('gulp-uglify'); //js圧縮

//js圧縮
gulp.task('js', function(done) {
    gulp.src("../resource/js/*.js")
    .pipe(uglify())
    // .pipe(rename({
    //   suffix: '.min',
    // }))
    .pipe(gulp.dest("../../heroku/js"));
    done();
});

//scss
gulp.task('scss', function (done) {
    gulp.src("../resource/css/scss/*.scss")
    .pipe(sourcemaps.init())
    .pipe(sass({outputStyle: "expanded"}) //sコンパイル
    .on("error", sass.logError))
    .pipe(autoprefixer())  //ベンダープレフィックス付与
    .pipe(sourcemaps.write())
    .pipe(gulp.dest("../resource/css"))
    .pipe(cleanCSS()) //css圧縮
    // .pipe(rename({
    //   suffix: '.min',
    // }))
    .pipe(gulp.dest("../../heroku/css"))
    done();
});

// gulp.task('copy', function(done) {
//     gulp.src(["../resource/*.php","!../resource/db_connect.php","!../resource/index.php","!../resource/config.php","!../resource/ajax_icon_create.php"])
//     .pipe(gulp.dest("../../heroku"));
//     done();
// });

gulp.task('watch',function(){
  gulp.watch("../resource/css/scss", gulp.task('scss'));
  gulp.watch("../resource/js",  gulp.task('js'));
  // gulp.watch('../**/*.php', gulp.task('copy'));
});

gulp.task("default",gulp.task('watch'));
