<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BitcoinTransaction;
use App\Http\Controllers\BitcoinValue;
use App\Http\Controllers\Carbon;
use App\Http\Controllers\Controller;
use App\Services\BitcoinService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

final class BitcoinQuoteController extends Controller
{
    public function __construct(
        protected BitcoinService $bitcoinService,
    ) {

    }

    public function getBitcoinQuote(): JsonResponse
    {
        try {
            $bitcoinQuote = $this->bitcoinService->getQuote();

            return response()->json(['btc_quote' => [
                'buy' => $bitcoinQuote['ticker']['buy'],
                'sell' => $bitcoinQuote['ticker']['sell'],
                'date' => $bitcoinQuote['ticker']['date'],
            ]]);
        } catch (\Exception $e) {

            dd($e);
            Log::error($e->getMessage());

            return response()->json('Houve um erro ao cadastrar o usuário', $e->getCode());
        }
    }
}
