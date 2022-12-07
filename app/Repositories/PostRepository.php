<?php

namespace App\Repositories;

use App\Exceptions\GeneralJsonException;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class PostRepository
{
    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {
            $created = Post::query()->create([
                'title' => data_get($attributes, 'title'),
                'body' => data_get($attributes, 'body'),
            ]);

            $created->users()->sync(data_get($attributes, 'user_ids'));
            return $created;
        });
    }

    /**
     * @param array $attributes
     * @param Post $post
     * @return Post $post
     * @throws \Throwable
     */
    public function update(array $attributes, Post $post): Post
    {
        $updated = DB::transaction(function () use ($attributes, $post) {
            $updated = $post->update([
                'title' => data_get($attributes, 'title') ?? $post->title,
                'body' => data_get($attributes, 'body') ?? $post->body,
            ]);

            if ($user_ids = data_get($attributes, 'user_ids')) {
                $post->users()->sync($user_ids);
            }

            return $updated;
        });

        throw_if(!$updated, GeneralJsonException::class, 'Failed to update Post');
        return $post;
    }

    /**
     * @param Post $post
     * @return bool|null
     * @throws \Throwable
     */
    public function delete(Post $post)
    {
        $deleted =  $post->forceDelete();
        throw_if(!$deleted, GeneralJsonException::class, 'Unable to delete post');
        return $deleted;
    }
}
