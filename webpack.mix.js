const mix = require('laravel-mix')
const path = require('path')

const directory = path.basename(path.resolve(__dirname))
const source = `platform/plugins/${directory}`
const dist = `public/vendor/core/plugins/${directory}`

mix
    .sass(`${source}/resources/sass/tiktok-feed.scss`, `${dist}/css`)
    .js(`${source}/resources/js/tiktok-feed.js`, `${dist}/js`)

if (mix.inProduction()) {
    mix
        .copy(`${dist}/css/tiktok-feed.css`, `${source}/public/css`)
        .copy(`${dist}/js/tiktok-feed.js`, `${source}/public/js`)
}
