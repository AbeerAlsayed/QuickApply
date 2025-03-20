<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubmissionResource;
use App\Models\Country;
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

    public function notifyCompanies(Request $request)
    {
        try {
            $validated = $request->validate([
                'country_id' => 'required|exists:countries,id',
            ]);
            if (!auth()->check()) {
                return $this->sendError('Unauthorized. Please log in.', [], 401);
            }
            $notifiedCompanies = $this->submissionService->notifyCompanies(
                auth()->id(),
                $validated['country_id']
            );

            return $this->sendSuccess($notifiedCompanies, 'Notifications and emails sent successfully.');
        } catch (\Exception $e) {
            return $this->sendError('An error occurred while sending notifications.', ['error' => $e->getMessage()], 500);
        }
    }

    public function getCompaniesByCountry($countryId)
    {
        $country = Country::find($countryId);

        if (!$country) {
            return $this->sendError('Country not found', [], 404);
        }
        if (!auth()->check()) {
            return $this->sendError('Unauthorized. Please log in.', [], 401);
        }
        $userId = auth()->id();
        $companies = $country->companies()
            ->with(['positions', 'users' => function ($query) use ($userId) {
                $query->where('user_id', $userId)->select('company_id', 'is_sent');
            }])
            ->get();

        return $this->sendSuccess(SubmissionResource::collection($companies), 'Companies retrieved successfully');
    }

}
