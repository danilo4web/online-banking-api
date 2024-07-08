<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    protected string $model = User::class;

    public function find(int $userId): User
    {
        return $this->model::find($userId);
    }

    public function create(array $attributes)
    {
        return $this->model::create($attributes);
    }
}
