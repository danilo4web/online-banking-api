<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepositRequest;
use App\Jobs\SendEmailJob;
use App\Mail\DepositEmail;
use App\Services\AccountService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

final class AccountDepositController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected AccountService $accountService,
    ) {

    }

    public function deposit(DepositRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $user = $this->userService->getUser();
            $newBalance = $this->accountService->deposit($data['amount'], 'Deposit');

            $message = "You have deposited R$ {$data['amount']} in your account. Your new Balance is: R$ {$newBalance}";

            $emailContent = $this->emailContent($message);
            dispatch(new SendEmailJob($user->email, $emailContent, $message));

            return response()->json([
                'balance' => $newBalance
            ]);
        } catch (\Exception $e) {

            dd($e);

            Log::error($e->getMessage());

            return response()->json('Something went wrong.', $e->getCode());
        }
    }

    private function emailContent(string $message): DepositEmail
    {
        return new DepositEmail($message);
    }
}
