<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContentReport extends Model
{
    use HasCompositePrimaryKey;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'content_report';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = array('content_id', 'user_id');

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    public function content() {
        return $this->belongsTo('App\Content', 'content_id');
    }
    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }
}