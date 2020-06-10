<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'location', 'birthday'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'email', 'remember_token', 'user_search','google_id'
    ];

    protected $appends = ['avatar', 'banner', 'birthday_short', 'url'];

    const MAX_EMAIL_LENGTH = 255;
    const MAX_NAME_LENGTH = 255;
    const MAX_LOCATION_LENGTH = 25;
    const MIN_PASSWORD_LENGTH = 8;
    const MAX_PASSWORD_LENGTH = 128;

    public function admin() {
        return $this->hasOne('App\Admin', 'admin_id');
    }

    public function friend_count() {
        return $this->belongsToMany('App\User', 'friend', 'user_from', 'user_to')->count() + 
                $this->belongsToMany('App\User', 'friend', 'user_to', 'user_from')->count();
    }    

    public function friends() {
        $friends_from = User::join('friend', 'user_id', '=', 'user_to')->where('user_from', $this->user_id);
        $friends_to = User::join('friend', 'user_id', '=', 'user_from')->where('user_to', $this->user_id);
        return $friends_from->union($friends_to);
    }  
    
    public function friends_with(User $user) {
        return Friend::where('user_from', $user->user_id)->where('user_to', $this->user_id)
            ->orWhere('user_to', $user->user_id)->where('user_from', $this->user_id)
            ->exists();
    }

    public function getAvatarAttribute() {
        $path = $this->getAvatarPath();

        if (file_exists(public_path($path)))
            return asset($path);
        return asset('storage/default_avatar.png');
    }

    public function getBannerAttribute() {
        $path = $this->getBannerPath();

        if (file_exists(public_path($path)))
            return asset($path);
        return asset('storage/default_banner.png');
    }

    public function getBirthdayShortAttribute() {
        if ($this->birthday == null)
            return null;
        return short_date($this->birthday);
    }

    public function getUrlAttribute() {
        return route('profile', user_route_params($this));
    }

    public function getAvatarPath() {
        $filename = hash("md5", $this->user_id . 'avatar');
        return 'storage/users/' . $filename . '.png';
    }

    public function getBannerPath() {
        $filename = hash("md5", $this->user_id . 'banner');
        return 'storage/users/' . $filename . '.png';
    }

    public function ban(){
        return $this->hasOne('App\UserBan','user_banned');
    }

    public function isGoogleAccount(){
        return $this->google_id != null;
    }
}
