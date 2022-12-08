<?php

namespace Tests\Unit;

use App\Exceptions\GeneralJsonException;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_create()
    {
        // 1. Define the goal
        //test if the create() will actually create a record in the db

        // 2. Replicate the env/restriction
        $repository = $this->app->make(PostRepository::class);

        // 3. Define the source of truth
        $payload = [
            'title' => 'heyaa',
            'body' => []
        ];
        // 4. Compare the result
        $result = $repository->create($payload);

        $this->assertSame(
            $payload['title'],
            $result->title,
            'Post created does not have the same title'
        );
    }

    public function test_update()
    {
        //GOal: make sure that we can update a post using the update method

        //env
        $repository = $this->app->make(PostRepository::class);

        $dummyPost = Post::factory(1)->create()[0];

        //Source of truth
        $payload = [
            'title' => 'updated',
        ];

        //compare
        $result = $repository->update($payload, $dummyPost);
        $this->assertSame(
            $payload['title'],
            $result->title,
            'Post updated does not have the updated title'
        );
    }

    public function test_delete_will_throw_exception_when_delete_post_doesnt_exist()
    {
        // Goal: Test delete abnormalities

        //env
        $repository = $this->app->make(PostRepository::class);
        $dummy = Post::factory()->make();

        $this->expectException(GeneralJsonException::class);
        $deleted = $repository->delete($dummy);

    }

    public function test_delete()
    {
        // Goal: test if forceDelete() is working

        // env
        $repository = $this->app->make(PostRepository::class);
        $dummy = Post::factory()->create();

        //compare
        $deleted = $repository->delete($dummy);

        //verify if it is deleted
        $found = Post::query()->find($dummy->id);

        $this->assertSame(null, $found, 'Post is not deleted');
    }
}
