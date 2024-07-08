<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\AccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

final class AccountBalanceController extends Controller
{
    public function __construct(
        protected AccountService $accountService,
    ) {

    }

    public function balance(): JsonResponse
    {
        try {
            $balance = $this->accountService->getBalance();

            return response()->json([
                'balance' => $balance
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'message' => 'Error fetching balance', 'error' => $e->getMessage()], 500);
        }
    }
}
