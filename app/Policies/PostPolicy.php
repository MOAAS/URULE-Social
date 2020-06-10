<?php

namespace App\Policies;

use App\User;
use App\Post;

use Illuminate\Support\Facades\Auth;

class PostPolicy
{
    public function show(?User $user, Post $post)
    {
        if (isset($user))
            return !$post->private || $post->content->author_id == $user->user_id || $user->friends_with($post->content->author) || $user->admin;
        return !$post->private;
    }

    public function show_feed(User $user)
    {
        return Auth::check();
    }

    public function create(User $user)
    {
        // Any user can create post
        return Auth::check();
    }

    public function update(User $user, Post $post)
    {
      return $user->user_id == $post->content->author_id;
    }

    public function delete(User $user, Post $post)
    {
      return $user->user_id == $post->content->author_id || $user->admin;
    }
}