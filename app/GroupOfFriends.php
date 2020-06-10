<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupOfFriends extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group_of_friends';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'group_id';

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    const MAX_NAME_LENGTH = 255;

    public function members() {
        return $this->belongsToMany('App\User', 'group_member', 'group_id', 'user_id')->orderBy('name');
    }

    public function owner() {
        return $this->belongsTo('App\User', 'owner_id');
    }
}