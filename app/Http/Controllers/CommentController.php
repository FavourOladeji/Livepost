<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $perPage = $request->pageSize ?? 20;
        $comments = Comment::query()->paginate($perPage);
        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreCommentRequest $request
     * @return CommentResource
     */
    public function store(StoreCommentRequest $request)
    {
        $created = Comment::query()->create([
            'user_id' => Auth::id(),
            'post_id' => $request->post_id,
            'body' => $request->body
        ]);

        return new CommentResource($created);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Comment $comment
     * @return CommentResource
     */
    public function show(Comment $comment)
    {
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateCommentRequest $request
     * @param \App\Models\Comment $comment
     * @return CommentResource|JsonResponse
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $updated = $comment->update([
            'body' => $request->body ?? $comment->body
        ]);

        if (!$updated) {
            return new JsonResponse([
                'errors' => [
                    'Unable to save the ' . class_basename($comment)
                ]
            ], 400);
        }

        return new CommentResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Comment $comment)
    {
        $deleted = $comment->forceDelete();

        if (!$deleted) {
            return new JsonResponse([
                'errors' => [
                    'Could not delete resource'
                ]
            ], 400);
        }

        return new JsonResponse([
            'data' => "success"
        ]);
    }
}
