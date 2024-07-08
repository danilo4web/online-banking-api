<?php

namespace App\Http\Controllers\API;

use App\Exceptions\InsufficientBitcoinException;
use App\Http\Controllers\BitcoinTransaction;
use App\Http\Controllers\BitcoinValue;
use App\Http\Controllers\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests\BitcoinSellRequest;
use App\Services\BitcoinService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

final class BitcoinSellController extends Controller
{
    public function __construct(
        protected BitcoinService $bitcoinService,
    ) {

    }

    public function sell(BitcoinSellRequest $request): JsonResponse
    {
        $amountInBTC = $request->input('amount');

        try {
            $newBalance = $this->bitcoinService->sellBitcoin($amountInBTC);

            return response()->json(['balance' => number_format($newBalance, 2, ',', '.')]);
        } catch (InsufficientBitcoinException $e) {
            Log::error($e->getMessage());

            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json('Something went wrong.', $e->getCode());
        }
    }
}
