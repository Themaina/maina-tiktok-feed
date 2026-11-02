<?php

namespace MainaSuperintelligence\TiktokFeed\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use MainaSuperintelligence\TiktokFeed\Support\TiktokUrl;

class TiktokVideo extends BaseModel
{
    protected $table = 'tiktok_feed_videos';

    protected $fillable = [
        'title',
        'video_url',
        'video_id',
        'product_url',
        'cover_image',
        'order',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'title' => SafeContent::class,
    ];

    protected static function booted(): void
    {
        static::saving(function (self $video): void {
            $video->video_id = static::extractVideoId($video->video_url);
            $video->order = (int) $video->order;
        });
    }

    public static function extractVideoId(?string $url): ?string
    {
        return TiktokUrl::videoId($url);
    }

    public function embedUrl(): ?string
    {
        return $this->video_id ? TiktokUrl::embedUrl($this->video_id) : null;
    }
}
