<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/ecs.php',
        __DIR__ . '/rector.php',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $rectorConfig->skip([
        '*/Fixture/*',
        '*/Source/*',
    ]);

    $rectorConfig->importNames();
    $rectorConfig->importShortClasses(false);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_74,
        SetList::CODING_STYLE,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::PRIVATIZATION,
        PHPUnitSetList::COMPOSER_BASED,
    ]);
};
