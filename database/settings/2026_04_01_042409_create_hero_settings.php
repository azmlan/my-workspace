<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('hero.full_name', '');
        $this->migrator->add('hero.tagline', '');
        $this->migrator->add('hero.bio_short', '');
        $this->migrator->add('hero.github_url', null);
        $this->migrator->add('hero.linkedin_url', null);
        $this->migrator->add('hero.twitter_url', null);
        $this->migrator->add('hero.email_display', null);
    }
};
