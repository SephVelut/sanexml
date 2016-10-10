var gulp = require('gulp'),
	  phpunit = require('gulp-phpunit'),
	  plumber = require('gulp-plumber');

gulp.task('phpunit', function() {
	var options = {debug: false, notify: false};
	gulp.src('phpunit.xml')
		.pipe(plumber())
		.pipe(phpunit('./vendor/bin/phpunit', options));
});

gulp.task('watch', function () {
	gulp.watch('tests/**/*.php', ['phpunit']);
	gulp.watch('src/**/*.php', ['phpunit']);
});

gulp.task('default', ['watch']);
