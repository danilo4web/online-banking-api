<?php

namespace App\Http\Controllers\API;

use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\InsufficientBitcoinException;
use App\Http\Controllers\BitcoinTransaction;
use App\Http\Controllers\BitcoinValue;
use App\Http\Controllers\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests\BitcoinPurchaseRequest;
use App\Http\Requests\BitcoinSellRequest;
use App\Services\BitcoinService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

final class BitcoinPositionController extends Controller
{
    public function __construct(
        protected BitcoinService $bitcoinService,
    ) {

    }

    public function position(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        try {
            $position = $this->bitcoinService->getInvestmentPosition($userId);
            return response()->json(compact('position'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json(['message' => 'Error fetching investment position', 'error' => $e->getMessage()], 500);
        }
    }
}
