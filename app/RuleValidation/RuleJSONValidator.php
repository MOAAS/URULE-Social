<?php

namespace App\RuleValidation;

use App\PostRule;

class RuleJSONValidator {
    const MAX_SUBSTR_LENGTH = 50;
    const MAX_DESC_LENGTH = 50;
    const MAX_JSON_LENGTH = 10000;
    const MAX_ERROR_LENGTH = 1000;

    public static function validate($jsonStr) {
        $json = json_decode($jsonStr, true);
        if ($json == null)
            abort(422, "Invalid JSON.");
        if (isset($json['ruleID'])) {
            if (count($json) > 1)
                abort(422, "Referenced rule must be the only JSON attribute.");
            if (PostRule::find($json['ruleID']) == null)
                abort(422, "Referenced rule does not exist");
            return $json;
        }

        //if (!isset($json['description']))
        //    abort(422, "Rule must have description");
        if (isset($json['description']) && strlen($json['description']) > self::MAX_DESC_LENGTH)
            abort(422, "Rule description must have no more than " . self::MAX_DESC_LENGTH . " characters.");
        if (isset($json['error']) && strlen($json['error']) > self::MAX_ERROR_LENGTH)
            abort(422, "Rule description must have no more than " . self::MAX_ERROR_LENGTH . " characters.");
        if (strlen($jsonStr) > self::MAX_JSON_LENGTH)
            abort(422, "Rule description must have no more than " . self::MAX_JSON_LENGTH . " characters.");

        self::validateAll($json);
        return $json;
    }

    private static function validateAll($rules) {
        foreach ($rules as $key => $value) {
            self::validateSingle($key, $value);
        }
        return true;
    }

    private static function validateSingle($ruleType, $value) {
        switch ($ruleType) {
            case "ruleID": return true;
            case "description": return true;
            case "error": return true;
            case "startsWith": return self::validateSubstring($value, "'Starts With'");
            case "startsWithCS":  return self::validateSubstring($value, "'Starts With'");
            case "endsWith":  return self::validateSubstring($value, "'Ends With'");
            case "endsWithCS": return self::validateSubstring($value, "'Ends With'");
            case "equals": return self::validateSubstring($value, "'Equals'");
            case "equalsCS": return self::validateSubstring($value, "'Equals'");
            case "contains": return self::validateSubstring($value, "'Contains'");
            case "containsCS": return self::validateSubstring($value, "'Contains'");
            case "vowels": return self::validateBoolean($value, "'Vowels'");
            case "regex": return self::validateSubstring($value, "'Regex'");
            case "length": return self::validateLength($value, "'Length'");
            case "occurrences": return self::validateOccurrences($value, "'Occurrences'");
            case "occurrencesCS": return self::validateOccurrences($value, "'Occurrences'");
            case "not": return self::validateNot($value, "'Not'");
            case "or": return self::validateLogicalOperator($value, "'Or'");
            case "and": return self::validateLogicalOperator($value, "'And'");
            case "if": return self::validateIf($value, "'If'");
            default: abort(422, "Unexpected JSON attribute: " . $ruleType);
        }
        return false;
    }

    private static function validateSubstring($value, $name) {
        if (!is_string($value))
            abort(422, $name . " value must be a String of characters");
        if (strlen($value) > self::MAX_SUBSTR_LENGTH)
            abort(422, $name . " value must be smaller than " . self::MAX_SUBSTR_LENGTH . " characters");
        return true;
    }

    private static function validateBoolean($value, $name) {
        if (!is_bool($value))
            abort(422, $name . " value must be true or false");
        return true;
    }

    private static function validateLength($value, $name) {
        $min = isset($value['min']) ? $value['min'] : 0;
        $max = isset($value['max']) ? $value['max'] : PHP_INT_MAX;

        if (!is_int($max) || !is_int($max))
            abort(422, $name . " : Max and Min must be positive integers.");
        if ($min > $max)
            abort(422, $name . " : Max can't be less than Min");
        if ($min < 0 || $max < 0)
            abort(422, $name . " : Max and Min must be positive integers.");
        return true;
    }

    private static function validateOccurrences($value, $name) {
        if (!isset($value['occurrence']))
            abort(422, $name . " : Occurrence is a mandatory field.");
        self::validateSubstring($value['occurrence'], $name);
        self::validateLength($value, $name);
        return true;
    }

    private static function validateNot($value, $name) {
        if (!self::validateJSONObject($value))
            abort(422, $name . " must contain a JSON object");
        return self::validateAll($value);
    }

    private static function validateLogicalOperator($value, $name) {
        if (!is_array($value))
            abort(422, $name . " must contain an array");

        foreach ($value as $object) {
            if (!self::validateJSONObject($object))
                abort(422, $name . " must contain only JSON objects");
            self::validateAll($object);
        }
        return true;
    }

    private static function validateIf($value, $name) {
        if (!isset($value['condition']))
            abort(422, $name . " : condition is a mandatory field.");
        if (!isset($value['then']))
            abort(422, $name . " : then is a mandatory field.");
        if (!isset($value['else']))
            abort(422, $name . " : else is a mandatory field.");

        if (!self::validateJSONObject($value['condition']))
            abort(422, $name . ": condition must be a JSON object");
        if (!self::validateJSONObject($value['then']))
            abort(422, $name . ": then must be a JSON object");
        if (!self::validateJSONObject($value['else']))
            abort(422, $name . ": else must be a JSON object");

        self::validateAll($value['condition']);
        self::validateAll($value['then']);
        self::validateAll($value['else']);
        return true;
    }

    private static function validateJSONObject($value) {
        return is_array($value) && array_key_first($value) !== 0;
    }


}