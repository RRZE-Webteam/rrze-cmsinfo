'use strict';

const
    {src, dest, watch, series} = require('gulp'),
    sass = require('gulp-sass'),
    cleancss = require('gulp-clean-css'),
    postcss = require('gulp-postcss'),
    autoprefixer = require('autoprefixer'),
    bump = require('gulp-bump'),
    semver = require('semver'),
    info = require('./package.json'),
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
}

exports.css = css;
exports.patchversion = series(patchPackageVersion, patchPluginVersion);
exports.default = startWatch;
