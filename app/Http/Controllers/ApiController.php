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

    // Check comment by ids
    public function checkPayment(Request $request){
        $ids = $request->ids;

        // Assume MonthlyPayment model imported globally
        $foundIds = \App\Models\MonthlyPayment::whereIn('id', $ids)->pluck('id')->toArray();

        // return not found ids
        $notFoundIds = array_diff($ids, $foundIds);
        return response()->json([
            'foundIds' => $foundIds,
            'notFoundIds' => $notFoundIds
        ]);
    }
}
