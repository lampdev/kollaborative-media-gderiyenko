<?php

namespace App\Jobs;

use App\Events\SubmissionSaved;
use App\Http\Requests\SubmitRequest;
use App\Models\Submission;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SaveSubmission implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected array $data
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $submission = Submission::create($this->data);
            SubmissionSaved::dispatch($submission);
        } catch (\Exception $e) {
            Log::error('Error saving submission', [
                'error' => $e->getMessage(),
                'data' => $this->data,
            ]);

            throw $e;
        }
    }
}
