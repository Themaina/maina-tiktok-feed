@php
    $position = $helper->getSetting('position', 'bottom-left');
    $draggable = (bool) $helper->getSetting('draggable', true);
    $showOnMobile = (bool) $helper->getSetting('show_on_mobile', true);
    $autoOpen = (bool) $helper->getSetting('auto_open', false);
    $autoOpenDelay = (int) $helper->getSetting('auto_open_delay', 5);
    $shopButtonText = $helper->getSetting('shop_button_text', trans('plugins/tiktok-feed::tiktok-feed.shop_button_text_default'));
    $shopButtonUrl = $helper->getSetting('shop_button_url') ?: $helper->defaultShopButtonUrl();
    $profileUrl = $helper->profileUrl();
    $followButtonText = $helper->getSetting('follow_button_text');
    $widgetSize = $helper->getSetting('widget_size', 'compact');
    $clickToLoad = (bool) $helper->getSetting('click_to_load', false);
    $mobileBottomOffset = max(0, min(300, (int) $helper->getSetting('mobile_bottom_offset', 20)));
@endphp

<div
    id="tiktok-feed-widget"
    class="tf-widget tf-position-{{ $position }} tf-size-{{ $widgetSize }} @if(! $showOnMobile) tf-hide-mobile @endif"
    data-draggable="{{ $draggable ? '1' : '0' }}"
    data-auto-open="{{ $autoOpen ? '1' : '0' }}"
    data-auto-open-delay="{{ $autoOpenDelay }}"
    data-stack-position="{{ $position }}"
    style="--tf-mobile-bottom: {{ $mobileBottomOffset }}px"
>
    <button type="button" class="tf-toggle" aria-expanded="false" aria-controls="tf-panel">
        <span class="tf-toggle-icon">
            <x-core::icon name="ti ti-brand-tiktok" class="tf-mark tf-mark-cyan" :wrapper="false" />
            <x-core::icon name="ti ti-brand-tiktok" class="tf-mark tf-mark-pink" :wrapper="false" />
            <x-core::icon name="ti ti-brand-tiktok" class="tf-mark tf-mark-white" :wrapper="false" />
        </span>
    </button>

    <div class="tf-panel" id="tf-panel">
        <button type="button" class="tf-close" aria-label="{{ trans('plugins/tiktok-feed::tiktok-feed.close') }}">
            <x-core::icon name="ti ti-x" :wrapper="false" />
        </button>

        <div class="tf-track" style="--tf-slides: {{ $videos->count() }}">
            @foreach ($videos as $video)
                <div class="tf-slide">
                    @if ($clickToLoad)
                        <button
                            type="button"
                            class="tf-facade"
                            @if ($video->cover_image) style="background-image: url('{{ RvMedia::getImageUrl($video->cover_image) }}')" @endif
                            aria-label="{{ trans('plugins/tiktok-feed::tiktok-feed.tap_to_play') }}"
                        >
                            <span class="tf-facade-play">
                                <x-core::icon name="ti ti-player-play-filled" :wrapper="false" />
                            </span>
                            <span class="tf-facade-label">{{ trans('plugins/tiktok-feed::tiktok-feed.tap_to_play') }}</span>
                        </button>
                    @endif
                    <iframe
                        class="tf-embed"
                        data-src="{{ $video->embedUrl() }}"
                        title="{{ $video->title ?: 'TikTok video' }}"
                        allow="encrypted-media; picture-in-picture"
                        allowfullscreen
                        scrolling="no"
                    ></iframe>
                </div>
            @endforeach
        </div>

        @if ($videos->count() > 1)
            <button type="button" class="tf-nav tf-nav-prev" aria-label="Previous">
                <x-core::icon name="ti ti-chevron-left" :wrapper="false" />
            </button>
            <button type="button" class="tf-nav tf-nav-next" aria-label="Next">
                <x-core::icon name="ti ti-chevron-right" :wrapper="false" />
            </button>

            <div class="tf-dots">
                @foreach ($videos as $index => $video)
                    <button
                        type="button"
                        class="tf-dot @if ($index === 0) is-active @endif"
                        data-index="{{ $index }}"
                        data-shop-url="{{ $video->product_url ?: $shopButtonUrl }}"
                    ></button>
                @endforeach
            </div>
        @endif

        <div class="tf-overlay-bottom">
            @if ($profileUrl)
                <a
                    href="{{ $profileUrl }}"
                    target="_blank"
                    rel="noopener nofollow"
                    class="tf-follow-btn @if(! $followButtonText) tf-follow-btn-icon @endif"
                    title="{{ $followButtonText ?: trans('plugins/tiktok-feed::tiktok-feed.follow_button_text') }}"
                >
                    <x-core::icon name="ti ti-brand-tiktok" :wrapper="false" />
                    @if ($followButtonText)
                        <span>{{ $followButtonText }}</span>
                    @endif
                </a>
            @endif
            <a
                href="{{ $videos->first()->product_url ?: $shopButtonUrl }}"
                class="tf-shop-btn"
                data-default-url="{{ $shopButtonUrl }}"
            >
                <x-core::icon name="ti ti-shopping-bag" :wrapper="false" />
                {{ $shopButtonText }}
            </a>
        </div>
    </div>
</div>
