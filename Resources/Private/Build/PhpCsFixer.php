<?php

use PhpCsFixer\Finder;
use PhpCsFixer\Config;
$baseDir = dirname(dirname(dirname(__DIR__)));

$finder = Finder::create()
    ->in($baseDir . '/Classes')
    ->in($baseDir . '/Configuration');

return Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PHP70Migration' => true,
        '@DoctrineAnnotation' => true,
        'concat_space' => [
            'spacing' => 'one',
        ],
        'general_phpdoc_annotation_remove' => [
            'annotations' => ['author', 'package', 'subpackage'],
        ],
        'linebreak_after_opening_tag' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_order' => true,
        'ordered_imports' => [
            'sortAlgorithm' => 'alpha',
        ],
        'array_syntax' => [
            'syntax' => 'short',
        ],
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'align_multiline_comment' => [
            'comment_type' => 'phpdocs_only',
        ],
        'no_null_property_initialization' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'no_homoglyph_names' => true,
        'declare_strict_types' => true,
        'mb_str_functions' => true,
        'native_function_invocation' => [
            'exclude' => [],
        ],
        'ordered_class_elements' => true,
        'no_short_echo_tag' => true,
        'no_superfluous_elseif' => true,
        'no_unreachable_default_argument_value' => true,
        'no_php4_constructor' => true,
        'simplified_null_return' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'no_leading_import_slash' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'no_unused_imports' => true,
        'no_whitespace_in_blank_line' => true,
        'single_quote' => true,
        'no_empty_statement' => true,
        'no_extra_consecutive_blank_lines' => true,
        'phpdoc_no_package' => true,
        'phpdoc_scalar' => true,
        'no_blank_lines_after_phpdoc' => true,
        'whitespace_after_comma_in_array' => true,
        'function_typehint_space' => true,
        'hash_to_slash_comment' => true,
        'no_alias_functions' => true,
        'lowercase_cast' => true,
        'no_leading_namespace_whitespace' => true,
        'native_function_casing' => true,
        'no_short_bool_cast' => true,
        'no_unneeded_control_parentheses' => true,
        'phpdoc_no_empty_return' => true,
        'phpdoc_trim' => true,
        'phpdoc_types' => true,
        'phpdoc_types_order' => ['null_adjustment' => 'always_last', 'sort_algorithm' => 'none'],
        'return_type_declaration' => ['space_before' => 'none'],
    ])
    ->setFinder($finder);
