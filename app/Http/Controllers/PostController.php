<?php

namespace App\Http\Controllers;

use App\Exceptions\GeneralJsonException;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Repositories\PostRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $perPage = $request->pageSize ?? 20;
        $posts = Post::query()->paginate($perPage);

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StorePostRequest $request
     * @return PostResource
     */
    public function store(StorePostRequest $request, PostRepository $postRepository)
    {
        $created = $postRepository->create($request->only([
            'title',
            'body',
            'user_ids'
        ]));


        return new PostResource($created);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Post $post
     * @return PostResource
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdatePostRequest $request
     * @param \App\Models\Post $post
     * @return PostResource|JsonResponse
     * @throws \Throwable
     */
    public function update(UpdatePostRequest $request, Post $post, PostRepository $postRepository)
    {
        $post = $postRepository->update($request->only([
            'title',
            'body',
            'user_ids'
        ]), $post);


        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function destroy(Post $post, PostRepository $postRepository)
    {
        $deleted = $postRepository->delete($post);

        return new JsonResponse([
            'data' => 'success'
        ]);
    }
}
