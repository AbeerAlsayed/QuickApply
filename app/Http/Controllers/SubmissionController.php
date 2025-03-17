<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubmissionResource;
use App\Models\Submission;
use App\Services\SubmissionService;
use Illuminate\Http\Request;

class SubmissionController extends BaseController
{
    protected $submissionService;

    public function __construct(SubmissionService $submissionService)
    {
        $this->submissionService = $submissionService;
    }

    public function getSubmissionsByCountry(Request $request, $countryName)
    {
        $submissions = Submission::whereHas('company.country', function ($query) use ($countryName) {
            $query->where('name', $countryName);
        })->get();
        return $this->sendSuccess(SubmissionResource::collection($submissions), 'Submissions retrieved successfully');
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
