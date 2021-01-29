<?php

/**
 * @var modX $modx
 */
require_once dirname(__DIR__) . '/modx.php';
require_once __DIR__ . '/build.config.php';

/** $builder modPackageBuilder */
$builder = $modx->loadClass('transport.modPackageBuilder', '', false, true);
$builder = new modPackageBuilder($modx);

/** Creating package */
require_once PKG_BUILD_TRANSPORT_PATH . 'package.inc.php';

/** Files */
require_once PKG_BUILD_TRANSPORT_PATH . 'files.inc.php';

/** Create .zip file */
$builder->pack();
$modx->log(modX::LOG_LEVEL_INFO, 'Package transport  zip created');
exit();
