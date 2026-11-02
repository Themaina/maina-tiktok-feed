<?php

return [
    [
        'name' => 'TikTok Feed',
        'flag' => 'tiktok-feed.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'tiktok-feed.create',
        'parent_flag' => 'tiktok-feed.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'tiktok-feed.edit',
        'parent_flag' => 'tiktok-feed.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'tiktok-feed.destroy',
        'parent_flag' => 'tiktok-feed.index',
    ],
    [
        'name' => 'Settings',
        'flag' => 'tiktok-feed.settings',
        'parent_flag' => 'tiktok-feed.index',
    ],
];
