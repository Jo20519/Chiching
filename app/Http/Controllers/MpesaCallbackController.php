<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MpesaCallbackController extends Controller
{public function result(Request $request)
    {
     // update withdrawal → PAID or FAILED
     Log::info('B2C Callback', $request->all());
    }
}
