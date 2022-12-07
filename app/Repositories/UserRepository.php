<?php

namespace App\Repositories;

use App\Events\UserCreated;
use App\Exceptions\GeneralJsonException;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    public function create(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {
            $created = User::query()->create([
                'name' => data_get($attributes, 'name'),
                'email' => data_get($attributes, 'email')
            ]);

            throw_if(!$created, GeneralJsonException::class, 'Failed to create user');
            event(new UserCreated($created));
            return $created;
        });
    }

    /**
     * @param array $attributes
     * @param User $user
     * @return bool|null
     * @throws \Throwable
     */
    public function update(array $attributes, User $user)
    {
        $updated = DB::transaction(function () use ($attributes, $user) {
            return $user->update([
                'name' => data_get($attributes, 'name'),
                'email' => data_get($attributes, 'email')
            ]);
        });
        throw_if(!$updated, GeneralJsonException::class, 'Unable to update the user');
        return $updated;
    }

    public function forceDelete(User $user)
    {
        $deleted = $user->forceDelete();
        throw_if(!$deleted, GeneralJsonException::class, 'Unable to delete the user');
        return $deleted;
    }
}
