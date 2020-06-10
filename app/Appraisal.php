<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appraisal extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'appraisal';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'appraisal_id';

    // Don't add create and update timestamps in database.
    public $timestamps  = false;
}