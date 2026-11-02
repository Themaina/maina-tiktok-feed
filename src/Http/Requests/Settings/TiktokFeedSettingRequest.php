<?php

namespace Maina888\TiktokFeed\Http\Requests\Settings;

use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Validator;
use Maina888\TiktokFeed\Support\TiktokFeedHelper;
use Maina888\TiktokFeed\Support\TiktokUrl;
use Illuminate\Validation\Rule;

class TiktokFeedSettingRequest extends Request
{
    public function rules(): array
    {
        $onOffRule = new OnOffRule();

        return [
            'enabled' => $onOffRule,
            'tiktok_username' => ['nullable', 'string', 'max:100'],
            'position' => ['required', Rule::in(['bottom-left', 'bottom-right', 'top-left', 'top-right'])],
            'draggable' => $onOffRule,
            'widget_size' => ['required', Rule::in(['compact', 'standard', 'spacious'])],
            'follow_button_text' => ['nullable', 'string', 'max:50'],
            'shop_button_text' => ['nullable', 'string', 'max:50'],
            'shop_button_url' => ['nullable', 'string', 'max:400'],
            'display_pages' => ['sometimes', 'required', 'array', Rule::in(array_keys(app(TiktokFeedHelper::class)->displayPages()))],
            'limit_videos' => ['nullable', 'integer', 'min:1', 'max:30'],
            'show_on_mobile' => $onOffRule,
            'click_to_load' => $onOffRule,
            'mobile_bottom_offset' => ['nullable', 'integer', 'min:0', 'max:300'],
            'auto_open' => $onOffRule,
            'auto_open_delay' => ['nullable', 'integer', 'min:1', 'max:120'],
        ];
    }

    /**
     * Checks that need PHP, kept out of rules() so the admin's client-side validation does not try to run them.
     */
    public function withValidator(Validator $validator): void
    {
        // Skip the admin's per-field remote checks; these rules run on the real submit.
        if ($this->has('_js_validation')) {
            return;
        }

        $validator->after(function (Validator $validator): void {
            $profile = (string) $this->input('tiktok_username');

            if ($profile !== '' && ! TiktokUrl::profileUrl($profile)) {
                $validator->errors()->add('tiktok_username', trans('plugins/tiktok-feed::tiktok-feed.tiktok_username_invalid'));
            }

            $shopUrl = trim((string) $this->input('shop_button_url'));

            if ($shopUrl !== '' && ! preg_match('#^(https?://|/)#i', $shopUrl)) {
                $validator->errors()->add('shop_button_url', trans('plugins/tiktok-feed::tiktok-feed.shop_button_url_invalid'));
            }
        });
    }
}
