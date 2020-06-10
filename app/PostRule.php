<?php

namespace App;

use App\RuleValidation\RuleJSONValidator;
use Illuminate\Database\Eloquent\Model;

class PostRule extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'post_rule';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'post_rule_id';

    // Don't add create and update timestamps in database.
    public $timestamps  = false;


    public function setJSON($jsonStr) {
        $json = json_decode($jsonStr, true);

        if (isset($json['description']))
            $this->rule_description = $json['description'];
        else $this->rule_description = "No description.";

        if (isset($json['error']))
            $this->error_message = $json['error'];

        $this->rule_json = $jsonStr;
    }

    public static function validateJSON($jsonStr) {
        return RuleJSONValidator::validate($jsonStr);
    }

    public function getErrorMessage($default) {
        if ($this->error_message == null)
            return $default;
        return $this->error_message;
    }
}