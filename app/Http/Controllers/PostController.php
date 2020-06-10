<?php

namespace App\Http\Controllers;

use App\RuleValidator;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Content;
use App\Post;
use App\PostRule;

use Carbon\Carbon;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    private function getHotPosts($offset, $limit) {
        return Content::with('post', 'author')
            ->join('post', 'post_id', '=', 'content_id')
            ->where('private', 'false')
            ->where('content_date', '>', DB::raw("now() - INTERVAL '2 days'"))
            ->orderBy("likes", "DESC")
            ->orderBy('content_date', 'DESC')
            ->offset($offset)
            ->take($limit)
            ->get();
    }

    private function getFeedPosts($offset, $limit) {
        $user_id = Auth::user()->user_id;
        return Content::with('post', 'author')
            ->join('post', 'post_id', '=', 'content_id')
            ->where('author_id', $user_id)
            ->orWhereExists(function ($query) use ($user_id) {
                $query->from(DB::raw("friends_of($user_id)"))->where('friend_id', DB::raw('author_id'));
            })
            ->orderBy('content_date', 'DESC')
            ->offset($offset)
            ->take($limit)
            ->get();
    }

    private function getPostComments($id, $offset, $limit) {
        return Content::with('comment', 'author')
            ->join('comment', 'content_id', '=', 'comment_id')
            ->where('post_id', $id)
            ->orderBy('content_date', 'ASC')
            ->offset($offset)
            ->take($limit)
            ->get();
    }

    public function hot_api(Request $request) {
        $request->validate(['offset' => 'integer|min:0', 'limit' => 'integer|min:1|max:500']);

        $offset = $request->offset ?: 0;
        $limit = $request->limit ?: 0;

        $posts = $this->getHotPosts($offset, $limit);
        return response()->json($posts);
    }

    public function feed_api(Request $request) {
        $this->authorize('show_feed', Post::class);

        $request->validate(['offset' => 'integer|min:0', 'limit' => 'integer|min:1|max:500']);

        $offset = $request->offset ?: 0;
        $limit = $request->limit ?: 0;


        $posts = $this->getFeedPosts($offset, $limit);
        return response()->json($posts);
    }

    public function post_api(Request $request, $id) {
        $post = Post::findOrFail($id);
        $this->authorize('show', $post);

        $request->validate(['offset' => 'integer|min:0', 'limit' => 'integer|min:1|max:500']);

        $offset = $request->offset ?: 0;
        $limit = $request->limit ?: 0;

        $comments = $this->getPostComments($id, $offset, $limit);

        return response()->json($comments);
    }


    public function hot() {
        $posts = $this->getHotPosts(0, 20);
        return view('pages.feed', ['user' => Auth::user(), 'posts' => $posts, 'hot' => true]);
    }

    public function feed() {
        if (!Auth::check())
            return redirect('/hot');

        $this->authorize('show_feed', Post::class);

        $posts = $this->getFeedPosts(0, 20);
        return view('pages.feed', ['user' => Auth::user(), 'posts' => $posts, 'hot' => false]);
    }

    public function show($id) {
        $post = Content::with('post', 'author')
            ->join('post', 'content_id', '=', 'post_id')
            ->where('content_id', $id)
            ->firstOrFail();

        $this->authorize('show', $post->post);

        $comments = $this->getPostComments($id, 0, 20);
        return view('pages.post', ['user' => Auth::user(), 'post' => $post, 'comments' => $comments]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', Post::class);

        $request->validate([
            'content' => 'required|max:' . Content::MAX_LENGTH,
            'private-post' => 'required|boolean',
            'image' => 'mimes:jpeg,jpg,png,gif|max:10000'
        ]);

        $content = new Content();
        $content->author_id = Auth::user()->user_id;
        $content->content = $request->input('content');
        $content->content_date = Carbon::now();

        $post = new Post();
        $post->private = $request->input('private-post');

        if ($request->filled('rule')) {
            $json = PostRule::validateJSON($request->rule);

            if (isset($json['ruleID'])) {
                $post->post_rule_id = $json['ruleID'];
            }
            else {
                $rule = new PostRule();
                $rule->setJSON($request->rule);
                $rule->save();
                $post->post_rule_id = $rule->post_rule_id;
            }
        }

        DB::transaction(function () use ($content, $post) {
            $content->save();
            $post->post_id = $content->content_id;
            $post->save();
        });

        if ($request->hasFile("image")) {
            Image::make($request->file('image'))->save($post->getImgPath(), 100);
        }

        $post->content->author;
        return response()->json($post);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('update', $post);
        $request->validate(['content' => 'required|max:' . Content::MAX_LENGTH]);

        $post->content->content = $request->input('content');
        $post->content->save();

        return response()->json($post);
    }

    public function delete(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $this->authorize('delete', $post);

        if (file_exists($post->getImgPath()))
            unlink($post->getImgPath());

        $post->content->delete();

        return response()->json($post);
    }
}
