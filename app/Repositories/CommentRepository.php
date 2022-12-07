<?php

namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Support\Facades\DB;

class CommentRepository
{
    public function create(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {
            return Comment::query()->create([
                'user_id' => data_get($attributes, 'user_id'),
                'body' => data_get($attributes, 'body')
            ]);
        });
    }

    public function update(array $attributes, Comment $comment)
    {

    }

    public function forceDelete(Comment $comment)
    {

    }
}
