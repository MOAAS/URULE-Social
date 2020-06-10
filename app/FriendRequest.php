<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FriendRequest extends Model
{
    use HasCompositePrimaryKey;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'friend_request';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = array('user_from', 'user_to');

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    public function from() {
        return $this->belongsTo('App\User', 'user_from');
    }

    public function to() {
        return $this->belongsTo('App\User', 'user_to');
    }

    public static function user_requests() {
        return FriendRequest::where('user_to', '=', Auth::check() ? Auth::user()->user_id : null);
    }
}