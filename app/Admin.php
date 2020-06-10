<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'admin_id';

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    public function user() {
        return $this->belongsTo('App\User', 'admin_id');
    }

    public function announcements() {
        return $this->hasMany('App\Announcement', 'author_id');
    }
}
