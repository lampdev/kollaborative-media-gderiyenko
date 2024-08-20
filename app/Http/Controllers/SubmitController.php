<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitRequest;
use App\Jobs\SaveSubmission;
use Illuminate\Http\Request;

class SubmitController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SubmitRequest $request)
    {
        SaveSubmission::dispatch($request->all());

        return response()->json(['success' => true, 'message' => 'Submission received'], 200);
    }
}
