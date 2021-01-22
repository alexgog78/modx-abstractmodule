<?php

/**
 * @var modX $modx
 * @var modPackageBuilder $builder
 */

$builder->createPackage(PKG_NAME_LOWER, PKG_VERSION, PKG_RELEASE);
$builder->setPackageAttributes([
    'changelog' => file_get_contents(PKG_PATH . 'docs/changelog.txt'),
    'license' => file_get_contents(PKG_PATH . 'docs/license.txt'),
    'readme' => file_get_contents(PKG_PATH . 'docs/readme.txt'),
    'requires' => [
        'php' => '>=7.0',
        'modx' => '>=2.4',
    ],
]);
