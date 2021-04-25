<?php

namespace Frontkom\NovaMediaLibrary;

use Illuminate\Http\Request;
use Kongulov\NovaTabTranslatable\NovaTabTranslatable;
use Kongulov\NovaTabTranslatable\TranslatableTabToRowTrait;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Resource;
use Frontkom\NovaMediaLibrary\Filters\Collection;

class Media extends Resource
{
    use TranslatableTabToRowTrait;

    public static $model = '\Frontkom\NovaMediaLibrary\Models\Media';
    public static $displayInNavigation = false;
    public static $search = ['collection_name', 'path', 'file_name', 'title', 'alt', 'mime_type'];


    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Media';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'file_name';

    /**
     * Get the displayable singular label of the resource.
     * Used is buttons but also in polymorphic labels.
     * @return string
     */
    public static function singularLabel(): string
    {
        return 'Media';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {

        if ($request->input('simple')) {
            $mainFields = [
                ID::make(),
                Image::make('Preview', 'thumbnail_path')->disk('media')->readonly(),
                Text::make('Name', 'file_name')->readonly(),
            ];
        } else {
            $mainFields = [
                ID::make(),
                Image::make('Preview', 'thumbnail_path')->disk('media')->hideWhenCreating(),
                Text::make('Name', 'file_name')->readonly(),
            ];
        }

        $customFields = [
            NovaTabTranslatable::make([
                Text::make('Title', 'title'),
                Text::make('Alt', 'alt'),
            ]),
        ];

        return array_merge(
            $mainFields,
            $customFields,
            [
                UrlField::make('Url', 'url')->readonly()->exceptOnForms(),
                Text::make('Collection', 'collection_name'),
            ]
        );
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new Collection,
        ];
    }
}
