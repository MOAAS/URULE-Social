<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comment';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'comment_id';

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    public function content() {
        return $this->belongsTo('App\Content', 'comment_id');
    }

    public function post() {
        return $this->belongsTo('App\Post', 'post_id');
    }
}