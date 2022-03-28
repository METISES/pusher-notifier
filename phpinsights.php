<?php

declare(strict_types=1);

return [
    'preset' => 'symfony',
    'ide' => 'phpstorm',
    'add' => [
        NunoMaduro\PhpInsights\Domain\Metrics\Code\Code::class => [
            SlevomatCodingStandard\Sniffs\ControlStructures\RequireYodaComparisonSniff::class,
        ],
    ],
    'remove' => [
        SlevomatCodingStandard\Sniffs\Commenting\DocCommentSpacingSniff::class,
        SlevomatCodingStandard\Sniffs\ControlStructures\DisallowYodaComparisonSniff::class,
        SlevomatCodingStandard\Sniffs\Classes\SuperfluousInterfaceNamingSniff::class,
        SlevomatCodingStandard\Sniffs\Classes\SuperfluousAbstractClassNamingSniff::class,
        SlevomatCodingStandard\Sniffs\Classes\SuperfluousTraitNamingSniff::class,
        SlevomatCodingStandard\Sniffs\TypeHints\NullTypeHintOnLastPositionSniff::class,
        SlevomatCodingStandard\Sniffs\TypeHints\TypeHintDeclarationSniff::class,
        SlevomatCodingStandard\Sniffs\TypeHints\ParameterTypeHintSniff::class,
        SlevomatCodingStandard\Sniffs\TypeHints\PropertyTypeHintSniff::class,
        SlevomatCodingStandard\Sniffs\TypeHints\ReturnTypeHintSniff::class,
        SlevomatCodingStandard\Sniffs\Functions\UnusedParameterSniff::class,
        SlevomatCodingStandard\Sniffs\Namespaces\UnusedUsesSniff::class,
        SlevomatCodingStandard\Sniffs\PHP\UselessParenthesesSniff::class,
        SlevomatCodingStandard\Sniffs\PHP\UselessSemicolonSniff::class,
        PHP_CodeSniffer\Standards\Generic\Sniffs\Commenting\TodoSniff::class,
        PHP_CodeSniffer\Standards\Generic\Sniffs\Formatting\SpaceAfterNotSniff::class,
        PHP_CodeSniffer\Standards\Generic\Sniffs\PHP\DiscourageGotoSniff::class,
        PHP_CodeSniffer\Standards\PSR1\Sniffs\Classes\ClassDeclarationSniff::class,
        NunoMaduro\PhpInsights\Domain\Sniffs\ForbiddenSetterSniff::class,
        NunoMaduro\PhpInsights\Domain\Insights\ForbiddenNormalClasses::class,
        NunoMaduro\PhpInsights\Domain\Insights\ForbiddenTraits::class,
        SlevomatCodingStandard\Sniffs\Functions\StaticClosureSniff::class,
    ],
    'config' => [
        PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff::class => [
            'lineLimit' => 300,
            'absoluteLineLimit' => 300,
            'ignoreComments' => false,
        ],
        PhpCsFixer\Fixer\Import\OrderedImportsFixer::class => [
            'imports_order' => ['class', 'function', 'const'],
            'sort_algorithm' => 'alpha',
        ],
    ],
    'requirements' => [
        'min-quality' => 90,
        'min-complexity' => 50,
        'min-architecture' => 90,
        'min-style' => 90,
        'disable-security-check' => false,
    ],
];
