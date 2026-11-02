<?php

namespace MainaSuperintelligence\TiktokFeed\Forms\Settings;

use Botble\Base\Forms\FieldOptions\CheckboxFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\MultiCheckListField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Setting\Forms\SettingForm;
use MainaSuperintelligence\TiktokFeed\Http\Requests\Settings\TiktokFeedSettingRequest;
use MainaSuperintelligence\TiktokFeed\Support\TiktokFeedHelper;

class TiktokFeedSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $helper = app(TiktokFeedHelper::class);

        $this
            ->setSectionTitle(trans('plugins/tiktok-feed::tiktok-feed.settings.name'))
            ->setSectionDescription(trans('plugins/tiktok-feed::tiktok-feed.settings.description'))
            ->setValidatorClass(TiktokFeedSettingRequest::class)
            ->add(
                'enabled',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('plugins/tiktok-feed::tiktok-feed.enable'))
                    ->value($helper->getSetting('enabled', true))
                    ->helperText(trans('plugins/tiktok-feed::tiktok-feed.enable_helper'))
            )
            ->add(
                'tiktok_username',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/tiktok-feed::tiktok-feed.tiktok_username'))
                    ->placeholder('yourshop')
                    ->value($helper->getSetting('tiktok_username'))
                    ->helperText(trans('plugins/tiktok-feed::tiktok-feed.tiktok_username_helper'))
            )
            ->add(
                'position',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/tiktok-feed::tiktok-feed.position'))
                    ->choices($helper->positions())
                    ->selected($helper->getSetting('position', 'bottom-left'))
                    ->helperText(trans('plugins/tiktok-feed::tiktok-feed.position_helper'))
            )
            ->add(
                'draggable',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('plugins/tiktok-feed::tiktok-feed.draggable'))
                    ->value($helper->getSetting('draggable', true))
                    ->helperText(trans('plugins/tiktok-feed::tiktok-feed.draggable_helper'))
            )
            ->add(
                'widget_size',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/tiktok-feed::tiktok-feed.widget_size'))
                    ->choices($helper->sizes())
                    ->selected($helper->getSetting('widget_size', 'compact'))
                    ->helperText(trans('plugins/tiktok-feed::tiktok-feed.widget_size_helper'))
            )
            ->add(
                'follow_button_text',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/tiktok-feed::tiktok-feed.follow_button_text_label'))
                    ->value($helper->getSetting('follow_button_text'))
                    ->helperText(trans('plugins/tiktok-feed::tiktok-feed.follow_button_text_helper'))
            )
            ->add(
                'shop_button_text',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/tiktok-feed::tiktok-feed.shop_button_text'))
                    ->value($helper->getSetting('shop_button_text', trans('plugins/tiktok-feed::tiktok-feed.shop_button_text_default')))
            )
            ->add(
                'shop_button_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/tiktok-feed::tiktok-feed.shop_button_url'))
                    ->value($helper->getSetting('shop_button_url', $helper->defaultShopButtonUrl()))
                    ->helperText(trans('plugins/tiktok-feed::tiktok-feed.shop_button_url_helper'))
            )
            ->add(
                'display_pages[]',
                MultiCheckListField::class,
                [
                    'label' => trans('plugins/tiktok-feed::tiktok-feed.display_pages_label'),
                    'choices' => $helper->displayPages(),
                    'value' => old('display_pages', $helper->getDisplayPages()),
                    'inline' => true,
                    'help_block' => [
                        'text' => trans('plugins/tiktok-feed::tiktok-feed.display_pages_helper'),
                    ],
                ]
            )
            ->add(
                'limit_videos',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/tiktok-feed::tiktok-feed.limit_videos'))
                    ->value($helper->getSetting('limit_videos', 6))
                    ->helperText(trans('plugins/tiktok-feed::tiktok-feed.limit_videos_helper'))
            )
            ->add(
                'show_on_mobile',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('plugins/tiktok-feed::tiktok-feed.show_on_mobile'))
                    ->value($helper->getSetting('show_on_mobile', true))
                    ->helperText(trans('plugins/tiktok-feed::tiktok-feed.show_on_mobile_helper'))
            )
            ->add(
                'mobile_bottom_offset',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/tiktok-feed::tiktok-feed.mobile_bottom_offset'))
                    ->value($helper->getSetting('mobile_bottom_offset', 20))
                    ->helperText(trans('plugins/tiktok-feed::tiktok-feed.mobile_bottom_offset_helper'))
            )
            ->add(
                'click_to_load',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('plugins/tiktok-feed::tiktok-feed.click_to_load'))
                    ->value($helper->getSetting('click_to_load', false))
                    ->helperText(trans('plugins/tiktok-feed::tiktok-feed.click_to_load_helper'))
            )
            ->add(
                'auto_open',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->label(trans('plugins/tiktok-feed::tiktok-feed.auto_open'))
                    ->value($helper->getSetting('auto_open', false))
                    ->helperText(trans('plugins/tiktok-feed::tiktok-feed.auto_open_helper'))
                    ->attributes([
                        'data-bb-toggle' => 'collapse',
                        'data-bb-target' => '#tiktok-feed-auto-open-delay',
                    ])
            )
            ->add(
                'openAutoOpenWrapper',
                HtmlField::class,
                [
                    'html' => sprintf(
                        '<div id="tiktok-feed-auto-open-delay" style="display: %s">',
                        $helper->getSetting('auto_open', false) ? 'block' : 'none'
                    ),
                ]
            )
            ->add(
                'auto_open_delay',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/tiktok-feed::tiktok-feed.auto_open_delay'))
                    ->value($helper->getSetting('auto_open_delay', 5))
                    ->helperText(trans('plugins/tiktok-feed::tiktok-feed.auto_open_delay_helper'))
            )
            ->add(
                'closeAutoOpenWrapper',
                HtmlField::class,
                [
                    'html' => '</div>',
                ]
            );
    }
}
