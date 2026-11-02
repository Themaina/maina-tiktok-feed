<?php

namespace Maina888\TiktokFeed\Http\Controllers\Settings;

use Botble\Setting\Http\Controllers\SettingController;
use Maina888\TiktokFeed\Forms\Settings\TiktokFeedSettingForm;
use Maina888\TiktokFeed\Http\Requests\Settings\TiktokFeedSettingRequest;
use Maina888\TiktokFeed\Support\TiktokFeedHelper;
use Illuminate\Support\Arr;

class TiktokFeedSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/tiktok-feed::tiktok-feed.settings.name'));

        return TiktokFeedSettingForm::create()->renderForm();
    }

    public function update(TiktokFeedSettingRequest $request, TiktokFeedHelper $helper)
    {
        $helper->saveSettings(Arr::only($request->validated(), $helper->settingKeys()));

        return $this
            ->httpResponse()
            ->withUpdatedSuccessMessage();
    }
}
