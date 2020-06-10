<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    use HasCompositePrimaryKey;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group_member';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = array('user_id', 'group_id');

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function group() {
        return $this->belongsTo('App\GroupOfFriends', 'group_id');
    }
}