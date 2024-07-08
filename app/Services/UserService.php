<?php

namespace App\Services;

use App\Exceptions\UserNotCreatedException;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
    ) {

    }

    public function create(array $data): array
    {
        $user = $this->userRepository->create($data);

        if (!$user) {
            throw new UserNotCreatedException();
        }

        return $user->toArray();
    }

    public function getUser(): Authenticatable
    {
        return Auth::user();
    }
}

