<?php

namespace App\Http\Controllers;

use App\RuleValidation\RuleValidator;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Content;
use App\Post;
use App\Comment;
use App\PostNotification;

use Carbon\Carbon;
use PDOException;

class CommentController extends Controller
{
    public function create(Request $request, $id)
    {
        // Find
        $post = Post::findOrFail($id);

        // Validate
        $this->authorize('create', $post);
        $request->validate(['content' => 'required|max:' . Content::MAX_LENGTH]);

        // Create
        $content = new Content();
        $content->author_id = Auth::user()->user_id;
        $content->content = $request->input('content');
        $content->content_date = Carbon::now();

        $comment = new Comment();

        if ($post->rules != null)
            RuleValidator::validateComment($post->rules, $content, $post);

        try {
            DB::transaction(function () use ($content, $comment, $id) {
                $content->save();
                $comment->comment_id = $content->content_id;
                $comment->post_id = $id;
                $comment->save();
            });
        }
        catch (PDOException $e) {
            abort(403, $e->getMessage());
        }

        if($post->content->author_id != null && $post->content->author->user_id != Auth::user()->user_id) {
            $description = Auth::user()->name . " commented on your Post!";
            PostNotification::add($post->content->author->user_id, $description, $post->content->content_id);
        }
        $comment->content->author;
        return response()->json($comment);
    }

    public function update(Request $request, $post_id, $comment_id)
    {
        $comment = Comment::findOrFail($comment_id);
        if ($comment->post_id != $post_id)
            abort(404, 'Comment does not belong to the specified post.');

        // Validate
        $this->authorize('update', $comment);
        $request->validate(['content' => 'required|max:' . Content::MAX_LENGTH]);

        // Update
        $comment->content->content = $request->input('content');

        if ($comment->post->rules != null)
            RuleValidator::validateComment($comment->post->rules, $comment->content, $comment->post);

        try {
            $comment->content->save();
        } catch (PDOException $e) {
            abort(403, $e->getMessage());
        }


        return response()->json($comment);
    }

    public function delete(Request $request, $post_id, $comment_id)
    {
        $comment = Comment::findOrFail($comment_id);
        if ($comment->post_id != $post_id)
            abort(404, 'Comment does not belong to the specified post.');

        $this->authorize('delete', $comment);
        
        $comment->content->delete();
        return response()->json($comment);
    }
}
