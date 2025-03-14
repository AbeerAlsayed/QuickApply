<?php

namespace App\Http\Controllers;

use App\Services\SubmissionService;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    protected $submissionService;

    public function __construct(SubmissionService $submissionService)
    {
        $this->submissionService = $submissionService;
    }

    public function notifyCompanies(Request $request)
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
        ]);

        $notifiedCompanies = $this->submissionService->notifyCompanies(
            3,
            $validated['country_id']
        );

        return response()->json([
            'status' => true,
            'message' => 'Notifications and emails sent successfully.',
            'data' => $notifiedCompanies,
        ]);
    }
}
