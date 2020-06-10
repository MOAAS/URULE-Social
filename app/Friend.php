<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    use HasCompositePrimaryKey;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'friend';

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
}