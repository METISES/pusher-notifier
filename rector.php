<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets(
        [
            SetList::PHP_71,
            SetList::PHP_72,
            SetList::PHP_73,
            SetList::PHP_74,
            SetList::PHP_80,
            SetList::PHP_81,
            SetList::PSR_4,
            SetList::CODE_QUALITY,
            SetList::CODING_STYLE,
            SetList::PRIVATIZATION,
            SetList::UNWRAP_COMPAT,
            SetList::DEAD_CODE,
            SetList::EARLY_RETURN,
            SetList::NAMING,
            SetList::TYPE_DECLARATION,
            SetList::MONOLOG_20,
        ]
    );

    $rectorConfig->parallel();
    $rectorConfig->skip([
            __DIR__.'/vendor/*',
            __DIR__.'/phpinsights.php',
        ]
    );

    $rectorConfig->ruleWithConfiguration(Rector\Php74\Rector\LNumber\AddLiteralSeparatorToNumberRector::class, [
        [
            Rector\Php74\Rector\LNumber\AddLiteralSeparatorToNumberRector::LIMIT_VALUE => 100,
        ],
    ]);
};
