<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Models\PaymentProvider as Model;

class PaymentProvider extends Controller
{
    public function read(): JsonResponse
    {
        return response()->json(Model::all());
    }
}
