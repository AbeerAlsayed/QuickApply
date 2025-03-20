<?php

namespace App\Services;

use App\Models\Test;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestService
{
    public function storeTest(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {

                $testData = $this->generateAITest($data);

                if ($testData['status'] === 'error') {
                    throw new \Exception($testData['message']);
                }


                $test = Test::create([
                    'user_id' => auth()->id(),
                    'position' => $data['position'],
                    'difficulty' => $data['difficulty'],
                ]);

                return ['status' => 'success', 'questions' => $testData['data']['questions']];
            });
        } catch (\Exception $e) {
            Log::error("Error storing test: " . $e->getMessage());
            return ['status' => 'error', 'message' =>  $e->getMessage()];
        }
    }

    public function generateAITest(array $data)
    {
        $apiKey = env('OPENROUTER_API_KEY');
        if (empty($apiKey)) {
            Log::error("OpenRouter API Key is missing.");
            return [
                'status' => 'error',
                'message' => 'API Key is missing.',
            ];
        }

        $prompt = "Generate a numbered test in JSON format to evaluate a candidate's skills for the given position and difficulty level.
The output **must be valid JSON** with one key:
- 'questions': an array of questions, where each question is an object containing:
    - 'number': the question number.
    - 'question': the text of the question.
    - 'options': an array of answer options (if applicable, leave empty if the question is open-ended).
Number of questions: 10.
Candidate Test Details:
- Position: {$data['position']}
- Difficulty Level: {$data['difficulty']}

Please generate clear and concise questions that match the position and difficulty level.

**Output Example (strict JSON format):**
json
{
    \"questions\": [
        {
            \"number\": 1,
            \"question\": \"What is dependency injection in Laravel?\",
            \"options\": [\"A method to inject dependencies\", \"A design pattern\", \"None of the above\"]
        },
        {
            \"number\": 2,
            \"question\": \"Explain the role of middleware in Laravel.\",
            \"options\": []
        }
    ]
}
";
        try {
            $response = Http::timeout(30)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                'model'    => 'deepseek/deepseek-r1:free',
                'messages' => [['role' => 'user', 'content' => $prompt]],
            ]);

            Log::debug("AI Test API Response: " . $response->body());

            if ($response->failed()) {
                throw new \Exception('Error connecting to AI API.');
            }

            $result = $response->json();
            if (!isset($result['choices'][0]['message']['content'])) {
                Log::error("Unexpected API response: " . json_encode($result));
                throw new \Exception("Unexpected API response.");
            }
            $content = trim($result['choices'][0]['message']['content']);
            $content = preg_replace('/.*?({.*}).*/s', '$1', $content);
            $json = json_decode($content, true);


            if (
                !isset($json['questions']) ||
                !is_array($json['questions']) ||
                empty($json['questions'])
            ) {
                throw new \Exception("Invalid question format.");
            }

            foreach ($json['questions'] as $question) {
                if (
                    !isset($question['number'], $question['question'], $question['options']) ||
                    !is_int($question['number']) ||
                    !is_string($question['question']) ||
                    !is_array($question['options'])
                ) {
                    throw new \Exception("Invalid question format.");
                }
            }

            return ['status' => 'success', 'data' => $json];


        } catch (\Exception $e) {
            Log::error("Exception in AI API call: " . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }






}
