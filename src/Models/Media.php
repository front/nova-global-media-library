<?php

namespace Frontkom\NovaMediaLibrary\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $table = 'media_library';

    protected $fillable = [
        'collection_name',
        'path',
        'file_name',
        'webp_name',
        'alt',
        'mime_type',
        'file_size',
        'webp_size',
        'image_sizes',
        'data',
    ];

    protected $appends = ['url', 'webp_url'];

    private function getStorageDisk(): string
    {
        return config('nova-global-media-library.storage_disk', 'public');
    }

    public function getUrlAttribute()
    {
        return Storage::disk($this->getStorageDisk())->url($this->path . $this->file_name);
    }

    public function getWebpUrlAttribute()
    {
        return !empty($this->webp_name) ? Storage::disk($this->getStorageDisk())->url($this->path . $this->webp_name) : null;
    }

    public function getImageSizesAttribute($value)
    {
        $sizes = json_decode($value, true) ?? [];

        foreach ($sizes as $key => $size) {
            $sizes[$key]['url'] = Storage::disk($this->getStorageDisk())->url($this->path . $size['file_name']);
            if (config('nova-global-media-library.webp_enabled', true) && isset($size['webp_name'])) {
                $sizes[$key]['webp_url'] = Storage::disk($this->getStorageDisk())->url($this->path . $size['webp_name']);
            }
        }

        return $sizes;
    }

    public function getThumbnailPathAttribute()
    {
        $thumbnailFileName = $this->image_sizes['thumbnail']['file_name'] ?? null;
        return $thumbnailFileName ? $this->path . $thumbnailFileName : $this->getFilePathAttribute();
    }

    public function getFilePathAttribute()
    {
        return $this->path . $this->file_name;
    }

    public function getFileAttributes($value)
    {
        return json_decode($value, true) ?? [];
    }
}
