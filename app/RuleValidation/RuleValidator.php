<?php


namespace App\RuleValidation;



class RuleValidator
{
    public static function validateComment($rule, $comment, $post) {
        $ruleRules = json_decode($rule->rule_json, true);
        foreach ($ruleRules as $key => $value) {
            if (!self::validateSingle($key, $value, $comment, $post))
                abort(422, $rule->getErrorMessage("Comment does not respect rule."));// . $key . " -> " . var_export($value, true)));
        }
        return true;
    }

    private static function validateAll($rules, $comment, $post) {
        foreach ($rules as $ruleType => $value) {
            if (!self::validateSingle($ruleType, $value, $comment, $post))
                return false;
        }
        return true;
    }

    private static function validateSingle($ruleType, $value, $comment, $post) {
        $commentContent = $comment->content;

        switch ($ruleType) {
            case "ruleID": return true;
            case "description": return true;
            case "error": return true;
            case "startsWith": return self::validateStartsWith($commentContent, $value, false);
            case "startsWithCS": return self::validateStartsWith($commentContent, $value, true);
            case "endsWith": return self::validateEndsWith($commentContent, $value, false);
            case "endsWithCS": return self::validateEndsWith($commentContent, $value, true);
            case "equals": return self::validateEquals($commentContent, $value, false);
            case "equalsCS": return self::validateEquals($commentContent, $value, true);
            case "contains": return self::validateContains($commentContent, $value, false);
            case "containsCS": return self::validateContains($commentContent, $value, true);
            case "vowels": return self::validateVowels($commentContent, $value);
            case "regex": return self::validateRegex($commentContent, $value);
            case "length": return self::validateLength($commentContent, $value);
            case "occurrences": return self::validateOccurrences($commentContent, $value, false);
            case "occurrencesCS": return self::validateOccurrences($commentContent, $value, true);
            case "not": return !self::validateAll($value, $comment, $post);
            case "or": return self::validateOr($value, $comment, $post);
            case "and": return self::validateAnd($value, $comment, $post);
            case "if": return self::validateIf($value, $comment, $post);
            default: abort(422, "Unexpected JSON attribute: " . $ruleType);
        }
        return false;
    }


    private static function validateOr($conditions, $comment, $post)
    {
        foreach ($conditions as $rules) {
            if (self::validateAll($rules, $comment, $post))
                return true;
        }
        return false;
    }

    private static function validateAnd($conditions, $comment, $post)
    {
        foreach ($conditions as $rules) {
            if (!self::validateAll($rules, $comment, $post))
                return true;
        }
        return true;
    }

    private static function validateIf($if, $comment, $post)
    {
        if (self::validateAll($if['condition'], $comment, $post))
            return self::validateAll($if['then'], $comment, $post);
        return self::validateAll($if['else'], $comment, $post);
    }

    private static function validateStartsWith($comment, $expected, $caseSensitive) {
        $found = substr($comment, 0, strlen($expected));
        return self::validateEquals($found, $expected, $caseSensitive);
    }

    private static function validateEndsWith($comment, $expected, $caseSensitive) {
        if (strlen($expected) == 0)
            return true;
        $found = substr($comment, -strlen($expected));
        return self::validateEquals($found, $expected, $caseSensitive);
    }

    private static function validateEquals($comment, $value, $caseSensitive) {
        if ($caseSensitive)
            return strcmp($comment, $value) === 0;
        return strcasecmp($comment, $value) === 0;
    }

    private static function validateContains($comment, $value, $caseSensitive)     {
        if ($caseSensitive)
            return strpos($comment, $value)  !== false;
        return stripos($comment, $value) !== false;
    }

    private static function validateVowels($comment, $yes) {
        return self::validateRegex($comment, '/[aeiou]/i') == $yes;
    }

    private static function validateRegex($comment, $regex) {
        return preg_match($regex, $comment) === 1;
    }

    private static function validateLength($comment, $value)
    {
        $min = isset($value['min']) ? $value['min'] : 0;
        $max = isset($value['max']) ? $value['max'] : PHP_INT_MAX;
        return strlen($comment) >= $min && strlen($comment) <= $max;
    }

    private static function validateOccurrences($comment, $value, $caseSensitive)
    {
        $min = isset($value['min']) ? $value['min'] : 0;
        $max = isset($value['max']) ? $value['max'] : PHP_INT_MAX;
        $expected = $value['occurrence'];

        if ($caseSensitive)
            $count = substr_count($comment, $expected);
        else $count = substr_count(strtoupper($comment), strtoupper($expected));

        return $count >= $min && $count <= $max;
    }
}