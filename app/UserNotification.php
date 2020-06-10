<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class UserNotification extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notification_user';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'notification_user_id';

    // Don't add create and update timestamps in database.
    public $timestamps = false;

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
        //return the user to display on the notification (only in case it is a user notification)
    }

    public static function add($newFriend, $description, $user) {
        $notification = new Notification();
        $notification->date_of_notification = Carbon::now();
        $notification->user_id = $user;
        $notification->description = $description;

        $userNotification = new UserNotification();
        $userNotification->user_id = $newFriend;
        
        DB::transaction(function () use ($notification, $userNotification) {
            $notification->save();
            $userNotification->notification_user_id = $notification->notification_id;
            $userNotification->save();
        });
    }
}