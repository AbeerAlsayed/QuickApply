<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SubmissionController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id', // المستخدم
            'company_id' => 'required|exists:companies,id', // الشركة
            'email' => 'required|email', // البريد الإلكتروني
            'description' => 'nullable|string', // الوصف
        ]);

        // التحقق من أن الطلب لم يُرسل لهذه الشركة من قبل
        $existingSubmission = Submission::where('user_id', $data['user_id'])
            ->where('company_id', $data['company_id'])
            ->where('is_sent', true)
            ->first();

        if ($existingSubmission) {
            return response()->json(['message' => 'This company has already been contacted by the user.'], 400);
        }

        // إنشاء الطلب
        $submission = Submission::create([
            'user_id' => $data['user_id'],
            'company_id' => $data['company_id'],
            'email' => $data['email'],
            'description' => isset($data['description']) ? $data['description'] : 'Default description from user profile',
            'is_sent' => false,
        ]);

        // إرسال الإيميل
        $this->sendEmail($submission);

        return response()->json(['message' => 'Submission created and email sent successfully.', 'submission' => $submission]);
    }

    protected function sendEmail(Submission $submission)
    {
        $company = $submission->company;

        // إعداد البيانات للإيميل
        $emailData = [
            'email' => $submission->email,
            'description' => $submission->description,
        ];

        // إرسال الإيميل (بافتراض وجود إعدادات SMTP)
        Mail::raw($emailData['description'], function ($message) use ($company, $emailData) {
            $message->to($company->email)
                ->subject('Job Application')
                ->from($emailData['email']);
        });

        // تحديث حالة الطلب بعد الإرسال
        $submission->update(['is_sent' => true]);
    }
}
