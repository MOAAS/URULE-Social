<?php

namespace App\Policies;

use App\User;
use App\Post;
use App\Comment;

use Illuminate\Support\Facades\Auth;

class CommentPolicy
{
    public function create(User $user, Comment $post)
    {
        return Auth::check();
    }

    public function update(User $user, Comment $comment)
    {
      return $user->user_id == $comment->content->author_id;
    }

    public function delete(User $user, Comment $comment)
    {
      // Only owners or admins can delete content
      return $user->user_id == $comment->content->author_id || $user->admin;
    }
}