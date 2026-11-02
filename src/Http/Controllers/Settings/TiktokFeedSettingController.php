<?php

namespace MainaSuperintelligence\TiktokFeed\Http\Controllers\Settings;

use Botble\Setting\Http\Controllers\SettingController;
use MainaSuperintelligence\TiktokFeed\Forms\Settings\TiktokFeedSettingForm;
use MainaSuperintelligence\TiktokFeed\Http\Requests\Settings\TiktokFeedSettingRequest;
use MainaSuperintelligence\TiktokFeed\Support\TiktokFeedHelper;
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
