<?php

namespace Tests\Feature\Api\V1\Post;

use App\Events\PostCreated;
use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        // load data in the db
        $posts = Post::factory(10)->create();
        $postIds = $posts->map(fn($post) => $post->id);

        // call index endpoint
        $response = $this->json('get', route('posts.index'));

        // assert status
        $response->assertStatus(200);
        // verify records
        $data = $response->json('data');
        collect($data)->each(
            fn($post) => $this->assertTrue(in_array($post['id'], $postIds->toArray()))
        );
    }

    public function test_show()
    {
        $dummy = Post::factory()->create();
        $fillables = collect($dummy->getFillable());


        $response = $this->json('get', route('posts.show', $dummy->id));
        $response = $response->assertStatus(200)->json('data');

        $this->assertEquals(data_get($response, 'id'),
            $dummy->id,
            'Response ID is not the same as model id');

        $fillables->each(function ($fillable) use ($dummy, $response) {
            $this->assertSame(data_get($dummy, $fillable), data_get($response, $fillable));
        });
    }

    public function test_store()
    {
        Event::fake();
        $dummy = Post::factory()->make();

        $response = $this->json('post', route('posts.store'), $dummy->toArray());

        $result = $response->assertStatus(201)->json('data');
        Event::assertDispatched(PostCreated::class);
        $result = collect($result)->only(array_keys($dummy->getAttributes()));
        $result->each(function ($value, $field) use ($dummy) {
            $this->assertSame(data_get($dummy, $field),
                $value,
                "Filled input is not the same as the output");
        });

    }

    public function test_update()
    {
        $dummy = Post::factory()->create();
        $dummy2 = Post::factory()->make();
        $dummy3 = Post::factory()->make();

        $fillables = collect((new Post())->getFillable());

        $fillables->each(function ($fillableAttribute) use ($dummy, $dummy2) {
            $response = $this->json('patch',
                route('posts.update', $dummy->id),
                [$fillableAttribute => data_get($dummy2, $fillableAttribute)]
            );
            $result = $response->assertStatus(200)->json('data');
            $this->assertSame(
                data_get($dummy2, $fillableAttribute),
                data_get($dummy->refresh(), $fillableAttribute),
                "Failed to update model"
            );

        });

        $response = $this->json('patch', route('posts.update', $dummy->id), $dummy3->toArray());
        $result = $response->assertStatus(200)->json('data');
        $fillables->each(function ($fillableAttributes) use ($dummy3, $dummy) {
            $this->assertSame(data_get($dummy3, $fillableAttributes), data_get($dummy->refresh(), $fillableAttributes));
        });
    }

    public function test_delete()
    {
        $dummy = Post::factory()->create();

        $response = $this->json('delete', route('posts.destroy', $dummy->id));
        $result = $response->assertStatus(200)->json();

        $this->expectException(ModelNotFoundException::class);
        Post::query()->findOrFail($dummy->id);

    }
}
