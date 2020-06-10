<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'post';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'post_id';

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    protected $appends = ['img'];

    public function content() {
        return $this->belongsTo('App\Content', 'post_id');
    }

    public function rules() {
        return $this->belongsTo('App\PostRule', 'post_rule_id');
    }

    public function getImgPath() {
        $filename = hash("md5", $this->post_id . 'image');
        return 'storage/posts/' . $filename . '.png';
    }

    public function getImgAttribute() {
        $path = $this->getImgPath();
        if (file_exists(public_path($path))) {
            return asset($path);
        }

        return null;
    }

}