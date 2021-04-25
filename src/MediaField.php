<?php

namespace Frontkom\NovaMediaLibrary;

use Laravel\Nova\Fields\Field;
use Frontkom\NovaMediaLibrary\Models\Media;


class MediaField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'media-field';

    public $fieldsCallback;

    protected $multiple = false;

    protected $collection = null;

    protected $detailThumbnailSize = null;

    /**
     * @param $width - Width of the preview thumbnail in admin
     * @param null $height - Inherited from width when null
     * @return $this
     */
    public function compact($width = 36, $height = null) {
        $this->detailThumbnailSize = [$width, $height];
        return $this;
    }

    /**
     * Set the number of rows used for the textarea.
     *
     * @param  int $rows
     * @return $this
     */
    public function multiple()
    {
        $this->multiple = true;

        return $this;
    }


    public function collection($collection)
    {
        $this->collection = $collection;
        return $this;
    }

    /**
     *
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'multiple' => $this->multiple,
            'order' => $this->multiple,
            'displayCollection' => $this->collection,
            'collections' => config('nova-global-media-library.collections'),
            'detailThumbnailSize' => $this->detailThumbnailSize
        ]);
    }

    public function resolveResponseValue($fieldValue)
    {
        $query = Media::whereIn('id', explode(',', $fieldValue));

        if ($this->multiple) {
            return $query->orderByRaw("FIELD(id, $fieldValue)")->get();
        }

        return $query->first();
    }

    public function fields(callable $callback): self
    {
        $this->fieldsCallback = $callback;

        return $this;
    }

}
