<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Notification extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notification';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'notification_id';

    // Don't add create and update timestamps in database.
    public $timestamps = false;

    public function userNotification() {
        return $this->hasOne('App\UserNotification', 'notification_user_id');
    }

    public function postNotification() {
        return $this->hasOne('App\PostNotification', 'notification_content_id');
    }

    public static function get_notifications() {
        return Notification::where('user_id', Auth::user()->user_id)->orderBy('date_of_notification', 'desc');
    }
}