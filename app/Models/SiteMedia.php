<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SiteMedia extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'site_media';

    protected $fillable = [
        'key',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('hero_photo')->singleFile();
        $this->addMediaCollection('hero_cv')->singleFile();
        $this->addMediaCollection('about_photo')->singleFile();
    }

    public static function instance(string $key): self
    {
        return self::firstOrCreate(['key' => $key]);
    }
}
