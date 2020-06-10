<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'message';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'message_id';

    protected $appends = ['was_sent', 'timestamp'];

    public function getWasSentAttribute() {
        return $this->sender_id == Auth::user()->user_id;
    }

    public function other() {
        return $this->sender_id == Auth::user()->user_id ? $this->belongsTo('App\User', 'receiver_id') : $this->belongsTo('App\User', 'sender_id');
    }

    public function author() {
        return $this->belongsTo('App\User', 'sender_id');
    }

    public function getTimestampAttribute() {
        $today = new Carbon();
        $today->setTime(0, 0, 0);

        $match_date = new Carbon($this->date_sent);
        $match_date->setTime(0, 0, 0);

        $date = new Carbon($this->date_sent);

        $diff = $today->diff( $match_date );
        $diffDays = $diff->days;

        if($diffDays == 0)
            $timestamp = date("H:i", $date->getTimestamp());
        else if ($diffDays <= 6)
            $timestamp = date("l", $date->getTimestamp());
        else if ($diffDays < 365)
            $timestamp = date("d/m", $date->getTimestamp());
        else
            $timestamp = date("d/m/Y", $date->getTimestamp());

        return $timestamp;
    }

    public function receiver() {
        return $this->belongsTo('App\User', 'receiver_id');
    }

    // Don't add create and update timestamps in database.
    public $timestamps  = false;
}