// gulpプラグインの読み込み
const gulp = require("gulp");
const sass = require("gulp-sass");  //sassをコンパイル
const rename = require('gulp-rename');  //ファイルリネーム
const autoprefixer = require('gulp-autoprefixer');  //ベンダープレフィックスを付与
const sourcemaps = require('gulp-sourcemaps');  //sourcemapsの付与
const cleanCSS = require('gulp-clean-css'); //css圧縮
const uglify = require('gulp-uglify'); //js圧縮
var replace = require('gulp-replace');

//js圧縮
gulp.task('js', function(done) {
    gulp.src(["../resource/js/*.js","!../resource/js/signup.js"])
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

//================================
//    ↓本番環境書き換え注意↓
//================================
// var all = ["../resource/*.php"];
// var blacklist = [ "!../resource/ajax_icon_create.php",
//                   "!../resource/config.php",
//                   "!../resource/db_connect.php",
//                   "!../resource/header.php",
//                   "!../resource/index.php",
//                   "!../resource/login_form.php",
//                   "!../resource/signup_form.php",
//                   "!../resource/signup_process.php"];
//
// var target = all.concat(blacklist);
// //debug削除
// gulp.task('replace', function(done) {
//   gulp.src(target)
//   .pipe( replace(/debug.*(\r\n|\n|\r)/g,''))
//   .pipe(gulp.dest("../../heroku"));
//   done();
// });

gulp.task('watch',function(){
  gulp.watch("../resource/css/scss", gulp.task('scss'));
  gulp.watch("../resource/js",  gulp.task('js'));
});

gulp.task("default",gulp.task('watch'));
