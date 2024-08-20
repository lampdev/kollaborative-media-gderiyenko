<?php

namespace Tests\Unit;

use App\Events\SubmissionSaved;
use App\Http\Requests\SubmitRequest;
use App\Jobs\SaveSubmission;
use App\Listeners\LogSubmissionSaved;
use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Mockery;
use PHPUnit\Framework\TestCase;

class SubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function testSaveSubmission(): void
    {
        Log::shouldReceive('info')
            ->once()
            ->with('Submission saved successfully.', [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
            ]);

        $submission = new Submission([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'message' => 'This is a test message.',
        ]);

        $event = new SubmissionSaved($submission);
        $listener = new LogSubmissionSaved();
        $listener->handle($event);

        $this->assertTrue(true);
    }
}
