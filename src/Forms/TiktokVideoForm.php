<?php

namespace MainaSuperintelligence\TiktokFeed\Forms;

use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use MainaSuperintelligence\TiktokFeed\Http\Requests\TiktokVideoRequest;
use MainaSuperintelligence\TiktokFeed\Models\TiktokVideo;

class TiktokVideoForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(TiktokVideo::class)
            ->setValidatorClass(TiktokVideoRequest::class)
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/tiktok-feed::tiktok-feed.title'))
                    ->helperText(trans('plugins/tiktok-feed::tiktok-feed.title_helper'))
            )
            ->add(
                'video_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/tiktok-feed::tiktok-feed.video_url'))
                    ->required()
                    ->placeholder('https://www.tiktok.com/@yourshop/video/1234567890123456789')
                    ->helperText(trans('plugins/tiktok-feed::tiktok-feed.video_url_helper'))
            )
            ->add(
                'product_url',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/tiktok-feed::tiktok-feed.product_url'))
                    ->helperText(trans('plugins/tiktok-feed::tiktok-feed.product_url_helper'))
            )
            ->add(
                'cover_image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(trans('plugins/tiktok-feed::tiktok-feed.cover_image'))
                    ->helperText(trans('plugins/tiktok-feed::tiktok-feed.cover_image_helper'))
            )
            ->add(
                'order',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/tiktok-feed::tiktok-feed.order'))
                    ->defaultValue(0)
                    ->helperText(trans('plugins/tiktok-feed::tiktok-feed.order_helper'))
            )
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}
