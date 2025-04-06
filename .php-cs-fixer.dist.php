<?php
$finder = PhpCsFixer\Finder::create()
    ->exclude('var')
    ->exclude('vendor')
    ->exclude('translations')
    ->in(__DIR__)
;
return (new PhpCsFixer\Config)
    ->setRiskyAllowed(true)
    ->setRules([
        '@DoctrineAnnotation' => true,
        '@PHP71Migration' => true,
        '@PHP71Migration:risky' => true,
        '@PHPUnit60Migration:risky' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'align_multiline_comment' => [
            'comment_type' => 'phpdocs_only',
        ],
        'array_indentation' => true,
        'array_syntax' => [
            'syntax' => 'short',
        ],
        'braces' => [
            'allow_single_line_closure' => true,
        ],
        'declare_strict_types' => true,
        'doctrine_annotation_array_assignment' => [
            'operator' => '=',
        ],
        'doctrine_annotation_spaces' => [
            'after_array_assignments_equals' => false,
            'before_array_assignments_equals' => false,
        ],
        'native_function_invocation' => [
            'include' => [
                '@compiler_optimized',
            ],
        ],
        'no_extra_blank_lines' => [
            'tokens' => [
                'break',
                'continue',
                'curly_brace_block',
                'extra',
                'parenthesis_brace_block',
                'return',
                'square_brace_block',
                'throw',
                'use',
            ],
        ],
        'no_superfluous_phpdoc_tags' => false,
        'no_unreachable_default_argument_value' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_imports' => [
            'imports_order' => [
                'class',
                'function',
                'const',
            ],
            'sort_algorithm' => 'alpha',
        ],
        'phpdoc_align' => [
            'align' => 'vertical',
        ],
        'phpdoc_order' => true,
        'psr_autoloading' => true,
        'semicolon_after_instruction' => true,
        'strict_comparison' => true,
        'strict_param' => true,
        'ternary_to_null_coalescing' => true,
        'fully_qualified_strict_types' => true,
        'phpdoc_no_alias_tag' => false,
        'phpdoc_to_param_type' => true,
        'phpdoc_to_return_type' => true,
        'binary_operator_spaces' => ['operators' => ['|' => null]],
        'single_line_throw' => false,
        'php_unit_test_annotation' => false,
    ])
    ->setFinder($finder)
;
