<?php

return [
    'image_sizes' => [
        'thumbnail' => [
            'width' => 150,
            'height' => 150,
            'crop' => true
        ]
    ],

    // Set dimension size in pixels to limit image size (ie 2000 would resize 6000x3000 image to 2000x1500)
    'max_original_image_dimensions' => null,

    'webp_enabled' => true,

    'generate_video_thumbnails' => false,

    'collections' => [],

    'storage_disk' => 'media',

    'storage_path' => 'public/media/',

    'media_handler' => \Frontkom\NovaMediaLibrary\Classes\MediaHandler::class,

    'media_resource' => \Frontkom\NovaMediaLibrary\Media::class,

    'types' => [
        'default' => [

        ],
    ],
];
