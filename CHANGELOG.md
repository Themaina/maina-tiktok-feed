# Changelog

## 1.1.0
- First public release on the Botble Marketplace, free under the MIT License, by Maina 888 (Maina Superintelligence Ltd).
- Vendor namespace is now `MainaSuperintelligence\TiktokFeed`. The plugin id stays `mainasoftwares/tiktok-feed`, so existing installs update in place.
- New: privacy mode. A cover image with a play button stands in for each video, and nothing loads from TikTok until the visitor taps play.
- New: cover image per video, shown in privacy mode and in the admin list.
- New: "Distance from the bottom on phones" setting, to clear bottom navigation bars such as Shofy's.
- Faster pages: video embeds now load only when the panel is opened or a slide is shown, never on page load.
- Video links are checked when saving (full TikTok video address required; short `vm.tiktok.com` links are explained), and the TikTok handle accepts `yourshop`, `@yourshop` or a profile URL.
- The default shop link must be an `https://` address or a site path.
- The admin list shows the video URL, cover and display order; the display order is no longer reset to 0 when editing.
- Shop page options (shop listing, product, cart, checkout) only appear when the Ecommerce plugin is active, so the plugin works on sites without a shop.
- Escape closes the panel, and the carousel now slides the right way in right-to-left languages.
- Unit tests for link parsing.

## 1.0.0
- Initial release: floating TikTok video widget with carousel, follow and shop buttons, four positions, three sizes, page targeting, drag to move, auto-open and mobile toggle.
