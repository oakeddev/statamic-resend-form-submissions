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
    public static function title()
    {
        return __('Resend');
    }

    public function run($items, $values)
    {
        $site = Site::findByUrl(URL::previous()) ?? Site::default();

        /** @var \Illuminate\Support\Collection $items */
        $items->each(function (Submission $submission) use ($site) {
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

        $emailData = (bool)$item->form()->email();

        return $emailData;
    }
}
