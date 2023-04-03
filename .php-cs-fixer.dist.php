<?php

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
        'no_superfluous_phpdoc_tags' => false,
        'yoda_style' => false,
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_summary' => false,
        'phpdoc_no_package' => false,
        'array_indentation' => true,
        'single_line_throw' => false,
        'concat_space' => false,
        'increment_style' => false,
        'new_with_braces' => false,
        'single_trait_insert_per_statement' => false,
        'php_unit_method_casing' => ['case' => 'snake_case'],
    ])
    ->setFinder(PhpCsFixer\Finder::create()
        ->exclude('vendor')
        ->exclude('bootstrap/cache')
        ->exclude('resources')
        ->exclude('storage')
        ->exclude('node_modules')
        ->exclude('tools')
        ->in(__DIR__)
    );
