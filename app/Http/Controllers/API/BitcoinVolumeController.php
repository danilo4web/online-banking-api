<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BitcoinTransaction;
use App\Http\Controllers\BitcoinValue;
use App\Http\Controllers\Carbon;
use App\Http\Controllers\Controller;
use App\Services\BitcoinService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

final class BitcoinVolumeController extends Controller
{
    public function __construct(
        protected BitcoinService $bitcoinService,
    ) {

    }

    public function getBitcoinVolume(): JsonResponse
    {
        try {
            $today = \Carbon\Carbon::now()->toDateString();
            $volume = $this->bitcoinService->getVolume($today);

            return response()->json(compact('volume'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'message' => 'Error fetching bitcoin volume', 'error' => $e->getMessage()], 500);
        }
    }
}
