<?php

namespace Frontkom\NovaMediaLibrary;

use Laravel\Nova\Tool;

class NovaMediaLibrary extends Tool
{

    public function renderNavigation()
    {
        return view('nova-media::navigation');
    }

    public static function getImageSizes()
    {
        return array_merge(['thumbnail' => [
            'width' => 150,
            'height' => 150,
            'crop' => true,
        ]], config('nova-global-media-library.image_sizes', []));
    }
}
