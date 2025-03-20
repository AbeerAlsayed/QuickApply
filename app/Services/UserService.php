<?php

namespace App\Services;

use GuzzleHttp\Client;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http; // لاستخدام API

class UserService
{


    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                throw new \Exception('Password is required.');
            }

            // التعامل مع السيرة الذاتية إذا كانت موجودة
            if (isset($data['cv']) && $data['cv'] instanceof \Illuminate\Http\UploadedFile) {
                try {
                    // التحقق من نوع الملف (مثال: PDF, DOCX, DOC)
                    $allowedMimeTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                    $cvMimeType = $data['cv']->getMimeType();

                    if (!in_array($cvMimeType, $allowedMimeTypes)) {
                        throw new \Exception("Invalid file type. Allowed types: PDF, DOC, DOCX.");
                    }

                    // التحقق من حجم الملف (مثال: الحد الأقصى 2MB)
                    $maxSize = 2048; // بالكيلوبايت
                    if ($data['cv']->getSize() > $maxSize * 1024) {
                        throw new \Exception("File size exceeds the maximum allowed size of 2MB.");
                    }

                    // تخزين الملف
                    $cvPath = $data['cv']->store('cvs', 'public');
                    $data['cv'] = $cvPath; // حفظ المسار
                } catch (\Exception $e) {
                    // في حال وقوع خطأ في رفع السيرة الذاتية
                    Log::error("CV upload error: " . $e->getMessage());
                    return response()->json([
                        'error' => 'CV upload failed.',
                        'message' => $e->getMessage(),
                        'mime_type' => isset($cvMimeType) ? $cvMimeType : 'N/A',
                        'file_size' => isset($data['cv']) ? $data['cv']->getSize() : 'N/A',
                    ], 400); // إرسال الخطأ مع التفاصيل إلى الـ frontend
                }
            }

            // التحقق من الرسالة (message) إذا كانت فارغة
            if (empty($data['message'])) {
                $messageData = $this->generateAIMessage($data);
                $data['message'] = $messageData['body'];
            }

            // إنشاء المستخدم
            $user = User::create($data);
            $token = $user->createToken('auth_token')->plainTextToken;
            return [
                'user' => $user,
                'token' => $token,
            ];
        });
    }

    private function generateAIMessage(array $data): array
    {
        $apiKey = env('OPENROUTER_API_KEY');
        if (empty($apiKey)) {
            Log::error("OpenRouter API Key is missing.");
            return [
                'subject' => "Job Application",
                'body' => "API Key is missing.",
            ];
        }

        $skills = is_array($data['skills']) ? implode(', ', $data['skills']) : $data['skills'];

        $prompt = "Generate a well-structured job application email in JSON format.
    The output **must be valid JSON** with two keys:
    - 'subject': A short, clear subject for the email.
    - 'body': A full, professional email content.

    Candidate Details:
    - Name: {$data['name']}
    - Education: {$data['education']}
    - Experience: {$data['experience']}
    - Skills: {$skills}
    - Position: {$data['position']}

    **Output Example (strict JSON format):**
    ```json
    {
        \"subject\": \"Application for Laravel Developer Position\",
        \"body\": \"Dear Hiring Manager, ... Regards, John Doe\"
    }
    ```";

        try {
            $response = Http::timeout(30)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                'model'    => 'deepseek/deepseek-r1:free',
                'messages' => [['role' => 'user', 'content' => $prompt]],
            ]);

            Log::debug("OpenRouter Response: " . $response->body());

            if ($response->failed()) {
                Log::error("Error connecting to OpenRouter API: " . $response->body());
                return [
                    'subject' => "Job Application",
                    'body' => "Error connecting to OpenRouter API.",
                ];
            }

            $result = $response->json();

            if (!isset($result['choices'][0]['message']['content'])) {
                Log::error("Unexpected API response: " . json_encode($result));
                return [
                    'subject' => "Job Application",
                    'body' => "Unexpected API response.",
                ];
            }

            // 🔹 استخراج النص الذي يتوقع أن يكون JSON
            $content = trim($result['choices'][0]['message']['content']);

            // 🔹 إزالة أي نصوص غير JSON من البداية أو النهاية
            $content = preg_replace('/.*?({.*}).*/s', '$1', $content);

            // 🔹 تحويل النص إلى JSON
            $json = json_decode($content, true);

            if (!isset($json['subject']) || !isset($json['body'])) {
                Log::error("Invalid JSON structure from API: " . json_encode($json));
                return [
                    'subject' => "Job Application",
                    'body' => "Invalid API response format.",
                ];
            }

            return [
                'subject' => $json['subject'],
                'body' => $json['body'],
            ];

        } catch (\Exception $e) {
            Log::error("Exception in OpenRouter API call: " . $e->getMessage());
            return [
                'subject' => "Job Application",
                'body' => "An error occurred while generating the message.",
            ];
        }
    }


    public function update(User $user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {
            // تحديث كلمة المرور فقط إذا تم تمريرها
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']); // لا تقم بتحديث كلمة المرور إذا لم يتم إرسالها
            }

            // إذا كان هناك ملف جديد في البيانات
            if (array_key_exists('cv', $data) && $data['cv'] instanceof \Illuminate\Http\UploadedFile) {
                // حذف الملف القديم إذا كان موجودًا
                if ($user->cv) {
                    Storage::disk('public')->delete($user->cv);
                }

                // رفع الملف الجديد وتخزين مساره
                $data['cv'] = $data['cv']->store('cvs', 'public');
            }

            // تحديث بيانات المستخدم
            $user->update($data);

            return $user;
        });
    }

    public function delete(User $user)
    {
        return DB::transaction(function () use ($user) {
            // حذف ملف السيرة الذاتية إذا كان موجودًا
            if ($user->cv) {
                Storage::disk('public')->delete($user->cv);
            }

            return $user->delete();
        });
    }
}
