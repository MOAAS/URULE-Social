<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use App\Appraisal;

class Content extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'content';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'content_id';

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    const MAX_LENGTH = 1000;

    protected $appends = ['content_date_short', 'is_liked', 'is_disliked', 'formatted_content', 'url'];

    public function post() {
        return $this->hasOne('App\Post', 'post_id');
    }

    public function comment() {
        return $this->hasOne('App\Comment', 'comment_id');
    }

    public function author() {
        return $this->belongsTo('App\User', 'author_id')->withDefault([
            'user_id' => -1,
            'name' => '[Deleted]',
        ]);
    }

    public function getIsLikedAttribute() {
        return Auth::check() ? Appraisal::where('user_id', Auth::user()->user_id)->where('content_id', $this->content_id)->where('like', 'true')->exists() : false;
    }

    public function getIsDislikedAttribute() {
        return Auth::check() ? Appraisal::where('user_id', Auth::user()->user_id)->where('content_id', $this->content_id)->where('like', 'false')->exists() : false;
    }

    public function getContentDateShortAttribute() {
        return short_timestamp($this->content_date);
    }

    public function getUrlAttribute() {
        if($this->post)
            return route('post', ['name' => str_replace(' ', '', $this->author->name), 'id' => $this->content_id]);
        else if ($this->comment)
            return $this->comment->post->content->getUrlAttribute();
        return 'NULL';
    }

    public function getFormattedContentAttribute() {
        $escapedContent = htmlentities($this->content);

        $pattern = '/\[(.*)\]\((.*)\)/';
        $replacement = '<a href="$2">$1</a>';
        $formatted = preg_replace($pattern, $replacement, $escapedContent);

        $pattern = '/((http|ftp|https):\/\/([\w_-]+(?:(?:\.[\w_-]+)+))([\w.,@?^=%&:\/~+#-]*[\w@?^=%&\/~+#-])?)/';
        $replacement = '<a href="$1" target="_blank">$1</a>';
        $formatted = preg_replace($pattern, $replacement, $formatted);

        return $formatted;
    }
}
