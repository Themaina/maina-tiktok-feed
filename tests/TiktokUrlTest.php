<?php

namespace MainaSuperintelligence\TiktokFeed\Tests;

use MainaSuperintelligence\TiktokFeed\Support\TiktokUrl;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TiktokUrlTest extends TestCase
{
    public static function videoLinks(): array
    {
        return [
            ['https://www.tiktok.com/@yourshop/video/7123456789012345678', '7123456789012345678'],
            ['https://www.tiktok.com/@your.shop/video/7123456789012345678?is_from_webapp=1&sender_device=pc', '7123456789012345678'],
            ['https://m.tiktok.com/v/7123456789012345678.html', null],
            ['https://www.tiktok.com/embed/v2/7123456789012345678', '7123456789012345678'],
            ['https://www.tiktok.com/player/v1/7123456789012345678', '7123456789012345678'],
            ['  7123456789012345678 ', '7123456789012345678'],
            ['https://vm.tiktok.com/ZMabcdef/', null],
            ['https://example.com/@yourshop/video/7123456789012345678', null],
            ['https://www.tiktok.com/@yourshop', null],
            ['', null],
            [null, null],
        ];
    }

    #[DataProvider('videoLinks')]
    public function test_it_extracts_the_video_id(?string $url, ?string $id): void
    {
        $this->assertSame($id, TiktokUrl::videoId($url));
    }

    public function test_it_builds_the_embed_url(): void
    {
        $this->assertSame('https://www.tiktok.com/embed/v2/7123456789012345678', TiktokUrl::embedUrl('7123456789012345678'));
    }

    public static function handles(): array
    {
        return [
            ['yourshop', 'https://www.tiktok.com/@yourshop'],
            ['@your.shop_1', 'https://www.tiktok.com/@your.shop_1'],
            ['https://www.tiktok.com/@yourshop?lang=en', 'https://www.tiktok.com/@yourshop'],
            ['', null],
            [null, null],
            ['your shop', null],
            ['"><script>', null],
        ];
    }

    #[DataProvider('handles')]
    public function test_it_builds_the_profile_url(?string $handle, ?string $url): void
    {
        $this->assertSame($url, TiktokUrl::profileUrl($handle));
    }
}
