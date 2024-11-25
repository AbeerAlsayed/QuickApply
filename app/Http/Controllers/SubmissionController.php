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
            'cv' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'description' => 'nullable|string',
            'position' => 'required|string',
        ]);

        $notifiedCompanies = $this->submissionService->notifyCompanies(
            1,
            $validated['country_id'],
            $request->file('cv'),
            $validated['description'],
            $validated['position']
        );

        return response()->json([
            'status' => true,
            'message' => 'Notifications and emails sent successfully.',
            'data' => $notifiedCompanies,
        ]);
    }
}
