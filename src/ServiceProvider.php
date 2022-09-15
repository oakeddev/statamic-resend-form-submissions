<?php

declare(strict_types=1);

namespace Oaked\ResendFormSubmissions;

use Oaked\ResendFormSubmissions\Actions\ResendFormSubmissions;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $actions = [
        ResendFormSubmissions::class
    ];

    public function bootAddon()
    {
        parent::boot();

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'resend-form-submissions');
    }
}
