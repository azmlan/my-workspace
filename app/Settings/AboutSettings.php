<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AboutSettings extends Settings
{
    public string $bio_full;

    public static function group(): string
    {
        return 'about';
    }
}
