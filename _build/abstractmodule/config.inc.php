<?php

$basePath = dirname(dirname(dirname(__FILE__)));

/** @noinspection PhpIncludeInspection */
require_once $basePath . '/config.core.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';

$modx = new modX();
$modx->initialize('mgr');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');
