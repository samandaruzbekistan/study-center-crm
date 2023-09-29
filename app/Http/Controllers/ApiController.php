<?php

namespace App\Http\Controllers;

use App\Services\SmsService;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function __construct(protected SmsService $smsService)
    {
    }

    public function sendSms(Request $request){
        $this->smsService->apiSMS($request->phone, $request->code);
    }
}
