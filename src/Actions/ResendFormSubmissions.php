<?php

declare(strict_types=1);

namespace Oaked\ResendFormSubmissions\Actions;

use Illuminate\Support\Facades\URL;
use Statamic\Actions\Action;
use Statamic\Facades\Site;
use Statamic\Forms\SendEmails;
use Statamic\Forms\Submission;

class ResendFormSubmissions extends Action
{
    private const INPUT_OVERWRITE = 'overwrite';
    private const INPUT_EMAIL = 'email';

    public static function title()
    {
        return __('Resend');
    }

    protected function fieldItems()
    {
        return [
            self::INPUT_OVERWRITE => [
                'type' => 'html',
                'html' => 'Fill in the input below to overwrite the original email address.'
            ],
            self::INPUT_EMAIL => [
                'type' => 'text',
                'validate' => 'email'
            ]
        ];
    }

    public function run($items, $values)
    {
        $overriddenEmail = $values[self::INPUT_EMAIL] ?? null;
        $site = Site::findByUrl(URL::previous()) ?? Site::default();

        /** @var \Illuminate\Support\Collection $items */
        $items->each(function (Submission $submission) use ($overriddenEmail, $site) {
            if ($overriddenEmail !== null) {
                $submission->data()->put('form_email', $overriddenEmail);
            }
            SendEmails::dispatch($submission, $site);
        });
    }

    public function confirmationText()
    {
        /** @translation */
        return 'Are you sure you want to resend this submission?|Are you sure you want to resend these :count submissions?';
    }

    public function visibleTo($item): bool
    {
        if (!$item instanceof Submission) {
            return false;
        }

        return (bool)$item->form()->email();
    }
}
