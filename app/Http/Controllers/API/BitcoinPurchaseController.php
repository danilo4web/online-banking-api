<?php

namespace App\Http\Controllers\API;

use App\Exceptions\InsufficientBalanceException;
use App\Http\Controllers\BitcoinTransaction;
use App\Http\Controllers\BitcoinValue;
use App\Http\Controllers\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests\BitcoinPurchaseRequest;
use App\Services\BitcoinService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

final class BitcoinPurchaseController extends Controller
{
    public function __construct(
        protected BitcoinService $bitcoinService,
    ) {

    }

    public function purchase(BitcoinPurchaseRequest $request): JsonResponse
    {
        $amountInBRL = $request->input('amount');

        try {
            $newBalance = $this->bitcoinService->buyBitcoin($amountInBRL);
        } catch (InsufficientBalanceException $e) {
            Log::error($e->getMessage());

            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json('Something went wrong.', $e->getCode());
        }

        return response()->json(['balance' => $newBalance]);
    }
}
