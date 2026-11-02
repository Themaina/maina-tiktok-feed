<?php

namespace Maina888\TiktokFeed\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Maina888\TiktokFeed\Forms\TiktokVideoForm;
use Maina888\TiktokFeed\Http\Requests\TiktokVideoRequest;
use Maina888\TiktokFeed\Models\TiktokVideo;
use Maina888\TiktokFeed\Tables\TiktokVideoTable;

class TiktokVideoController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/tiktok-feed::tiktok-feed.name'), route('tiktok-feed.index'));
    }

    public function index(TiktokVideoTable $table)
    {
        $this->pageTitle(trans('plugins/tiktok-feed::tiktok-feed.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/tiktok-feed::tiktok-feed.create'));

        return TiktokVideoForm::create()->renderForm();
    }

    public function store(TiktokVideoRequest $request)
    {
        $form = TiktokVideoForm::create()->setRequest($request);
        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousRoute('tiktok-feed.index')
            ->setNextRoute('tiktok-feed.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(TiktokVideo $video)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $video->title ?: $video->video_url]));

        return TiktokVideoForm::createFromModel($video)->renderForm();
    }

    public function update(TiktokVideo $video, TiktokVideoRequest $request)
    {
        TiktokVideoForm::createFromModel($video)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->setPreviousRoute('tiktok-feed.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(TiktokVideo $video)
    {
        return DeleteResourceAction::make($video);
    }
}
