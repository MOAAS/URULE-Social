<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class PostNotification extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notification_content';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'notification_content_id';

    // Don't add create and update timestamps in database.
    public $timestamps = false;

    public function content() {
        //return the user to display on the notification (only in case it is a user notification)
        return $this->belongsTo('App\Content', 'content_id');
    }

    public static function add($user, $description, $content) {
        $notification = new Notification();
        $notification->date_of_notification = Carbon::now();
        $notification->user_id = $user;
        $notification->description = $description;

        
        $contentNotification = new PostNotification();
        $contentNotification->content_id = $content;

        DB::transaction(function () use ($notification, $contentNotification) {
            $notification->save();
            $contentNotification->notification_content_id = $notification->notification_id;
            $contentNotification->save();
        });
    }
}