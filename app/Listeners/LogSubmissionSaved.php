<?php

namespace App\Listeners;

use App\Events\SubmissionSaved;
use Illuminate\Support\Facades\Log;

class LogSubmissionSaved
{
    /**
     * Handle the event.
     */
    public function handle(SubmissionSaved $event): void
    {
        $submission = $event->submission;
        Log::info('Submission saved successfully.', [
            'name' => $submission->name,
            'email' => $submission->email,
        ]);
    }
}
