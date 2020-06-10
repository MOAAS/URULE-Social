<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Announcement extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'announcement';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'announcement_id';

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    const MAX_LENGTH = 500;

    protected $appends = ['time_left'];

    public function author() {
        return $this->belongsTo('App\Admin', 'author_id');
    }

    public static function active_announcements() {
        return Announcement::where("duration_secs", '>', DB::raw("date_part('epoch', now() - date_of_creation)"))->orderBy('date_of_creation', 'desc');
    }

    public function getTimeLeftAttribute() {
        return time_left($this->date_of_creation, $this->duration_secs);
    }
}
