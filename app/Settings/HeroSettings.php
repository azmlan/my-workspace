<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class HeroSettings extends Settings
{
    public string $full_name;
    public string $tagline;
    public string $bio_short;
    public ?string $github_url;
    public ?string $linkedin_url;
    public ?string $twitter_url;
    public ?string $email_display;

    public static function group(): string
    {
        return 'hero';
    }
}
