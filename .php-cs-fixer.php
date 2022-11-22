<?php

$finder = (new PhpCsFixer\Finder())
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'php_unit_method_casing' => ['case' => 'snake_case'],
        'no_superfluous_phpdoc_tags' => false,
        'single_line_throw' => false,
        'phpdoc_trim_consecutive_blank_line_separation' => false,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => [ 'sort_algorithm' => 'length' ],
        'phpdoc_to_comment' => false,
    ])
    ->setFinder($finder)
    ;