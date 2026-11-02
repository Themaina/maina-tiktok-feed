<?php

namespace MainaSuperintelligence\TiktokFeed\Tables;

use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\ImageColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use MainaSuperintelligence\TiktokFeed\Models\TiktokVideo;
use Illuminate\Database\Eloquent\Builder;

class TiktokVideoTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(TiktokVideo::class)
            ->addColumns([
                IdColumn::make(),
                ImageColumn::make('cover_image')
                    ->title(trans('plugins/tiktok-feed::tiktok-feed.cover_image')),
                NameColumn::make('title')
                    ->title(trans('plugins/tiktok-feed::tiktok-feed.title'))
                    ->route('tiktok-feed.edit'),
                FormattedColumn::make('video_url')
                    ->title(trans('plugins/tiktok-feed::tiktok-feed.video_url'))
                    ->limit(60),
                Column::make('order')
                    ->title(trans('plugins/tiktok-feed::tiktok-feed.order'))
                    ->width(80),
                StatusColumn::make(),
                CreatedAtColumn::make(),
            ])
            ->addHeaderAction(CreateHeaderAction::make()->route('tiktok-feed.create'))
            ->addActions([
                EditAction::make()->route('tiktok-feed.edit'),
                DeleteAction::make()->route('tiktok-feed.destroy'),
            ])
            ->addBulkAction(DeleteBulkAction::make()->permission('tiktok-feed.destroy'))
            ->addBulkChanges([
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(function (Builder $query) {
                return $query
                    ->select([
                        'id',
                        'title',
                        'video_url',
                        'cover_image',
                        'status',
                        'created_at',
                        'order',
                    ])
                    ->orderBy('order')
                    ->orderByDesc('id');
            });
    }
}
