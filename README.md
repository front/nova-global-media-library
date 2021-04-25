# Nova Global Media Library

This [Laravel Nova](https://nova.laravel.com) package adds a simple image/gallery upload field with a media browser to Laravel Nova.

It is a fork of very good package [optimistdigital/nova-media-field](https://github.com/optimistdigital/nova-media-field)

## Requirements

- Imagick
- Laravel Nova >= 2.10.0

## Features:

- Media browser
- Drag-and-drop multi file upload
- Multiple file selection
- Drag and drop reordering of selected files
- Collections
- Thumbnail generator with custom sizes (also re-generation via command)
- WebP generator (also re-generation via command)
- Dynamic fields support
- Works well with Amazon S3


## Installation

Install the package in a Laravel Nova project via Composer and run migrations:

```bash
# Install package
composer require front/nova-global-media-library

# And then run migrations
php artisan migrate
```

And then register the `NovaMediaLibrary` tool in `NovaServiceProvider`:

```php
use Frontkom\NovaMediaLibrary\NovaMediaLibrary;

public function tools()
{
    return [
        new NovaMediaLibrary(),
    ];
}
```

## Usage

```php
use use Frontkom\NovaMediaLibrary\MediaField;;

// ...

fields() {
  return [
    MediaField::make('Profile image', 'profile_image'),

    // Configurable options:
    MediaField::make('Config example', 'config_example'),
      ->multiple() // Allows multiple images to tbe selected
      ->collection('profile-pictures') // Defines a fixed collection of images instead of a global scope
      ->compact($width, $height = null) // Defines the thumbnail image size shown in Nova (to actually change thumbnail image size, use config)
  ]
}
```

### Image thumbnails

Image thumbnails define different conversions for uploaded images. These conversions can be configured
under media field config file under `image_sizes` key.

```php
# config/nova-global-media-library.php

[
    'image_sizes' => [
        'thumbnail' => [
            'width' => 150,
            'height' => 150,
            'crop' => true
        ],
        'medium' => [
            'width' => 300
        ]
    ],
]
```

- `crop` - Default: `false`, when `true` then image might be cropped if not fit for defined ratio. Requires width and height to be defined.
- `width` - Width to resize the image
- `height` - Height to resize the image

Defining only one dimension (width or height) keeps the ratio.

### Video thumbnails

Media field can generate thumbnails from the first frame of the video. It uses `ffmpeg` and `php-ffmpeg` to achieve this.

To enable this, you must:

- Install `ffmpeg`
- Provide paths to `ffmpeg` and `ffprobe` (on some environments)

If `ffmpeg` and `ffprobe` paths are not automatically detected, add these variables to your ENV.

```bash
# NB! Including extension (ie .exe on Windows)
FFMPEG_PATH=/usr/local/bin/ffmpeg
FFPROBE_PATH=/usr/local/bin/ffprobe
```

### WebP support

By default WebP support is enabled in nova media config file. On image upload
the WebP will be generated automatically for you. If you have activated
or plan to activate it later then you can use commands below to regenerate
missing thumbnails and WebP files.

### Regenerate thumbnails

To regenerate thumbnails (after defining a new thumbnail size etc) run this command:

```bash
php artisan media:regenerate-thumbnails
```

### Regenerate WebP files

To regenerate your missing WebP files run this command:

```bsah
php artisan media:regenerate-webp
```

### Collections

Collections are basically upload groups that can have their own set of upload rules.
Collection configuration goes under media field config file under `collection` key.

```
# config/nova-global-media-library.php

[
    'collections' => [
        'banners' => [
            'label' => 'Banners',
            'constraints' => [
                'mimetypes' => [
                    'image/svg+xml',
                    'image/svg'
                ]
            ],
            'image_sizes' => [
                'thumbnail'
            ]
        ]
    ],
]

```

- `label` - Display label for collection
- `constraints` - Array of validation rules (like in Request validation)
- `image_sizes` - Sizes to generate (overrides default)

## Credits

- [Marttin Notta](https://github.com/marttinnotta)
- [Tarvo Reinpalu](https://github.com/Tarpsvo)
- [Michał Środek](https://github.com/michalsrodek)

## License

Nova Global Media Library is open-sourced software licensed under the [MIT license](LICENSE.md).
