<?php

$finder = (new PhpCsFixer\Finder())
    ->exclude('framework/var/')
    ->exclude('framework/vendor/')
    ->exclude('framework/public/bundles')
    ->in(__DIR__.'/..');

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules(
        [
            '@PSR1' => true,
            '@PSR2' => true,
            '@PHP70Migration' => true,
            '@PHP70Migration:risky' => true,
            '@PHP71Migration' => true,
            '@PHP71Migration:risky' => true,
            '@PHP73Migration' => true,
            '@PHP74Migration' => true,
            '@PHP74Migration:risky' => true,
            '@PHP80Migration' => true,
            '@PHP80Migration:risky' => true,
            '@PHP81Migration' => true,
            '@Symfony' => true,
            '@Symfony:risky' => true,
            '@DoctrineAnnotation' => true,
            '@PhpCsFixer' => true,
            '@PhpCsFixer:risky' => true,
            'array_syntax' => ['syntax' => 'short'],
            'no_superfluous_phpdoc_tags' => false,
            'global_namespace_import' => ['import_classes' => true, 'import_constants' => true, 'import_functions' => true],
            'group_import' => false,
            'ordered_imports' => ['imports_order' => ['class', 'function', 'const'], 'sort_algorithm' => 'alpha'],
            'fopen_flags' => ['b_mode' => true],
        ]
    )
    ->setFinder($finder);
