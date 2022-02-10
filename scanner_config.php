<?php

declare(strict_types=1);
/**
 *  Set $projectPath = getcwd(); if your put it under project root.
 */
$projectPath = getcwd();

/**
 * Array of full directories path for scan;
 * Scan will be recursive
 * Put directories with most intensive imports in top of list for more quick result.
 *
 * @see http://api.symfony.com/4.0/Symfony/Component/Finder/Finder.html#method_in
 */
$scanDirectories = [
    $projectPath,
];

$scanFiles = [];

$skipPackages = [
    'symfony/http-client',
    'symfony/notifier',
    'pusher/pusher-php-server',
];

/**
 * Names relative to ones of scanDirectories.
 *
 * @see http://api.symfony.com/4.0/Symfony/Component/Finder/Finder.html#method_exclude
 */
$excludeDirectories = [];

return [
    // Required params
    'composerJsonPath' => $projectPath.'/composer.json',
    'vendorPath' => $projectPath.'/vendor/',
    'scanDirectories' => $scanDirectories,

    // Optional params
    'skipPackages' => $skipPackages,
    'excludeDirectories' => $excludeDirectories,
    'scanFiles' => $scanFiles,
    'extensions' => ['*.php'],
    'requireDev' => false,
    'customMatch' => null,
    'reportPath' => null,
    'reportFormatter' => null,
    'reportExtension' => null,
];
