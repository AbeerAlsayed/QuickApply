<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestRequest;
use App\Services\TestService;
use Illuminate\Http\Request;

class TestController extends Controller
{
    protected $testService;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService ;

    }
    public function generateTest(TestRequest $request)
    {
        $data = $request->validated();
        return  $this->testService->storeTest($data);
    }
}
