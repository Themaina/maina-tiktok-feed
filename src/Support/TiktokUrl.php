<?php

namespace MainaSuperintelligence\TiktokFeed\Support;

/**
 * Parses TikTok links without any framework dependency, so it can be unit tested on its own.
 */
class TiktokUrl
{
    /**
     * The numeric video id from a TikTok video link (or a bare id), or null when there is none.
     * Short share links (vm.tiktok.com/…) carry no id: open them in a browser and copy the full address.
     */
    public static function videoId(?string $url): ?string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return null;
        }

        if (preg_match('/^(\d{5,25})$/', $url, $matches)) {
            return $matches[1];
        }

        if (! preg_match('#^https?://([a-z0-9-]+\.)*tiktok\.com/#i', $url)) {
            return null;
        }

        if (preg_match('#/(?:video|embed/v2|embed|player/v1)/(\d{5,25})(?:[/?&\#]|$)#', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    public static function embedUrl(string $videoId): string
    {
        return 'https://www.tiktok.com/embed/v2/' . rawurlencode($videoId);
    }

    /**
     * Profile link for a handle. Accepts "shop", "@shop" or a full profile URL; anything else gives null.
     */
    public static function profileUrl(?string $username): ?string
    {
        $username = trim((string) $username);

        if (preg_match('#tiktok\.com/@([A-Za-z0-9._]+)#i', $username, $matches)) {
            $username = $matches[1];
        }

        $username = ltrim($username, '@');

        return preg_match('/^[A-Za-z0-9._]{1,64}$/', $username) ? 'https://www.tiktok.com/@' . $username : null;
    }
}
