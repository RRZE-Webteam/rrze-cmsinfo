'use strict';

const
    {src, dest, watch, series} = require('gulp'),
    sass = require('gulp-sass'),
    cleancss = require('gulp-clean-css'),
    postcss = require('gulp-postcss'),
    autoprefixer = require('autoprefixer'),
    uglify = require('gulp-uglify'),
    babel = require('gulp-babel'),
    bump = require('gulp-bump'),
    semver = require('semver'),
    info = require('./package.json')
;

function css() {
    return src('./src/sass/*.scss', {
            sourcemaps: true
        })
        .pipe(sass())
        .pipe(postcss([autoprefixer()]))
        .pipe(cleancss())
        .pipe(dest('./css'));
}

function js() {
    return src('./src/js/*.js')
        .pipe(babel({
            presets: ['@babel/env']
        }))
        .pipe(uglify())
        .pipe(dest('./js'))
}

function patchPackageVersion() {
    var newVer = semver.inc(info.version, 'patch');
    return src(['./package.json'])
        .pipe(bump({
            version: newVer
        }))
        .pipe(dest('./'));
};

function patchPluginVersion() {
    var newVer = semver.inc(info.version, 'patch');
    return src('./' + info.main)
        .pipe(bump({
            version: newVer
        }))
        .pipe(dest('./'));
};

function startWatch() {
    watch('./src/sass/*.scss', css);
    watch('./gulpsrc/js/*.js', js);
}

exports.css = css;
exports.js = js;
exports.patchversion = series(patchPackageVersion, patchPluginVersion);
exports.default = startWatch;
