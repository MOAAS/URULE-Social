<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserBan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_ban';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'ban_id';

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    const MAX_LENGTH = 1000;

}