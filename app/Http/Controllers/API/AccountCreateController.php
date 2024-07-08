<?php

namespace App\Http\Controllers\API;

use App\Exceptions\AccountNotCreatedException;
use App\Exceptions\UserNotCreatedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccountCreateRequest;
use App\Services\AccountService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

final class AccountCreateController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected AccountService $accountService,
    ) {

    }

    public function create(AccountCreateRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();

            $user = $this->userService->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $this->accountService->create([
                'user_id' => $user['id']
            ]);

            DB::commit();

            return response()->json([
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'id' => $user['id']
                ],
                Response::HTTP_CREATED
            );
        } catch (UserNotCreatedException $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return response()->json($e->getMessage(), 500);
        } catch (AccountNotCreatedException $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return response()->json($e->getMessage(), 500);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return response()->json('Houve um erro ao cadastrar o usuário', 500);
        }
    }
}
