<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmissionRequest;
use App\Http\Resources\SubmissionResource;
use App\Services\SubmissionService;

class SubmissionController extends BaseController
{
    private $submissionService;

    public function __construct(SubmissionService $submissionService)
    {
        $this->submissionService = $submissionService;
    }

    public function store(SubmissionRequest $request)
    {
        $data = $request->validated(); // الحصول على البيانات بعد التحقق

        // إنشاء الطلب باستخدام الخدمة
        $submission = $this->submissionService->create($data);

        // إرسال الإيميل وتحديث حالة الطلب بعد الإرسال
        $this->submissionService->sendEmailAndUpdateStatus($submission);

        // إرجاع الاستجابة باستخدام الـ Resource
        return $this->sendSuccess(new SubmissionResource($submission), 'Submission created and email sent successfully.');
    }
}
