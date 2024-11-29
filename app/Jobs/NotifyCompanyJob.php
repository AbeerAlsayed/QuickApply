<?php

namespace App\Jobs;

use App\Models\Company;
use App\Notifications\JobSubmissionNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyCompanyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $company;
    protected $description;
    protected $position;
    protected $cvPath;
    protected $userEmail;

    public function __construct(Company $company, $description, $position, $cvPath, $userEmail)
    {
        $this->company = $company;
        $this->description = $description;
        $this->position = $position;
        $this->cvPath = $cvPath;
        $this->userEmail = $userEmail;
    }

    public function handle()
    {
        $this->company->notify(new JobSubmissionNotification(
            $this->description,
            $this->position,
            $this->cvPath,
            $this->userEmail
        ));
    }
}
