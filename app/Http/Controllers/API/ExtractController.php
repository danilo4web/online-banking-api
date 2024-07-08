<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExtractRequest;
use App\Services\AccountService;
use App\Services\BitcoinService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

final class ExtractController extends Controller
{
    public function __construct(
        protected AccountService $accountService,
        protected UserService    $userService,
        protected BitcoinService $bitcoinService,
    ) {

    }

    public function index(ExtractRequest $request): JsonResponse
    {
        $user = $this->userService->getUser();
        $startDate = $request->input('start_date') ?? now()->subDays(90);
        $endDate = $request->input('end_date') ?? now();

        try {
            $accountTransactions = $this->accountService->getTransactions($startDate, $endDate, $user->id);
            $bitcoinTransactions = $this->bitcoinService->getTransactions($user->id);

            return response()->json([
                'transactions' => $accountTransactions,
                'investments' => $bitcoinTransactions,
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'message' => 'Error fetching investment position', 'error' => $e->getMessage()], 500);
        }
    }
}
