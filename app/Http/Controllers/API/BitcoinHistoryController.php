<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BitcoinTransaction;
use App\Http\Controllers\BitcoinValue;
use App\Http\Controllers\Carbon;
use App\Http\Controllers\Controller;
use App\Services\BitcoinService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

final class BitcoinHistoryController extends Controller
{
    public function __construct(
        protected BitcoinService $bitcoinService,
    ) {

    }

    public function getHistoricalValues(): JsonResponse
    {
        try {
            $values = $this->bitcoinService->getHistoricalValues();

            return response()->json(compact('values'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'message' => 'Error fetching bitcoin history', 'error' => $e->getMessage()], 500);
        }
    }
}
