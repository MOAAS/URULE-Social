@include('partials.feed.rule_help.attribute', [
    'name' => 'description',
    'type' => 'String',
    'description' => "Sets the description for your rule.",
    'restriction' => "Maximum of " . \App\RuleValidation\RuleJSONValidator::MAX_ERROR_LENGTH . " characters."
])
@include('partials.feed.rule_help.attribute', [
    'name' => 'error',
    'type' => 'String',
    'description' => "Sets a custom error message. If someone doesn't respect the rule, it will be shown.",
    'restriction' => "Maximum of " . \App\RuleValidation\RuleJSONValidator::MAX_ERROR_LENGTH . " characters."
])
@include('partials.feed.rule_help.attribute', [
    'name' => 'startsWith',
    'type' => 'String',
    'description' => "Comment must begin with specified characters.",
    'restriction' => "Maximum of " . \App\RuleValidation\RuleJSONValidator::MAX_SUBSTR_LENGTH . " characters."
])
@include('partials.feed.rule_help.attribute', [
    'name' => 'startsWithCS',
    'type' => 'String',
    'description' => "Same as above, but case-sensitive.",
    'restriction' => "Same as above."
])
@include('partials.feed.rule_help.attribute', [
    'name' => 'endsWith',
    'type' => 'String',
    'description' => "Comment must end with specified characters.",
    'restriction' => "Maximum of " . \App\RuleValidation\RuleJSONValidator::MAX_SUBSTR_LENGTH . " characters."
])
@include('partials.feed.rule_help.attribute', [
    'name' => 'endsWithCS',
    'type' => 'String',
    'description' => "Same as above, but case-sensitive.",
    'restriction' => "Same as above."
])
@include('partials.feed.rule_help.attribute', [
    'name' => 'equals',
    'type' => 'String',
    'description' => "Comment must be exactly as specified.",
    'restriction' => "Maximum of " . \App\RuleValidation\RuleJSONValidator::MAX_SUBSTR_LENGTH . " characters."
])
@include('partials.feed.rule_help.attribute', [
    'name' => 'equalsCS',
    'type' => 'String',
    'description' => "Same as above, but case-sensitive.",
    'restriction' => "Same as above."
])
@include('partials.feed.rule_help.attribute', [
    'name' => 'contains',
    'type' => 'String',
    'description' => "Comment must contain specified characters.",
    'restriction' => "Maximum of " . \App\RuleValidation\RuleJSONValidator::MAX_SUBSTR_LENGTH . " characters."
])
@include('partials.feed.rule_help.attribute', [
    'name' => 'containsCS',
    'type' => 'String',
    'description' => "Same as above, but case-sensitive.",
    'restriction' => "Same as above."
])
@include('partials.feed.rule_help.attribute', [
    'name' => 'vowels',
    'type' => 'Boolean',
    'description' => "If true, comment must contain at least a vowel. Otherwise it must not contain them.",
    'restriction' => "None."
])
@include('partials.feed.rule_help.attribute', [
    'name' => 'regex',
    'type' => 'String',
    'description' => 'Comment must match specified regex at least once (this one is a bit more complicated, check <a href="https://regexr.com/">this website</a> out if you want more details.)',
    'restriction' => "Maximum of " . \App\RuleValidation\RuleJSONValidator::MAX_SUBSTR_LENGTH . " characters."
])
@include('partials.feed.rule_help.attribute', [
    'name' => 'length',
    'type' => 'Object',
    'description' => 'Comment must be at least "min", and at most "max" characters long. The object will be formatted like this {"min": 1, "max": 10}',
    'restriction' => '"min" and "max" must be of type Integer, and positive. "min" and "max" are both optional.'
])
@include('partials.feed.rule_help.attribute', [
    'name' => 'occurrences',
    'type' => 'Object',
    'description' => 'Comment must have at least "min", and at most "max" occurrences of "occurrence". The object will be formatted like this {"occurrence": "Hi", "min": 1, "max": 10}',
    'restriction' => 'Same as length attribute. "occurrence" has a Maximum of ' . \App\RuleValidation\RuleJSONValidator::MAX_SUBSTR_LENGTH . ' characters.'
])
@include('partials.feed.rule_help.attribute', [
    'name' => 'occurrencesCS',
    'type' => 'Object',
    'description' => "Same as above, but case-sensitive.",
    'restriction' => "Same as above."
])
@include('partials.feed.rule_help.attribute', [
    'name' => 'not',
    'type' => 'Object',
    'description' => "Comment must NOT obey the specified rule.",
    'restriction' => "Must be a valid rule, respecting all the previous restrictions."
])
@include('partials.feed.rule_help.attribute', [
    'name' => 'or',
    'type' => 'Array of Object',
    'description' => "Comment must obey AT LEAST ONE of the specified rules.",
    'restriction' => "All the elements must be valid rules, respecting all the previous restrictions."
])
@include('partials.feed.rule_help.attribute', [
    'name' => 'and',
    'type' => 'Array of Object',
    'description' => "Comment must obey ALL of the specified rules.",
    'restriction' => "All the elements must be valid rules, respecting all the previous restrictions."
])
@include('partials.feed.rule_help.attribute', [
    'name' => 'if',
    'type' => 'Object',
    'description' => 'If the comment obeys the rule in "condition", it must obey the rule in "then". Otherwise, it must obey the rule in "else". Similar structure to "occurrences".',
    'restriction' => "All the elements must be valid rules, respecting all the previous restrictions."
])
