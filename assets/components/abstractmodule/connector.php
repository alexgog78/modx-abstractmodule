<?php

/** @noinspection PhpIncludeInspection */
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';

$module = $modx->getService(PKG_NAME_LOWER, PKG_NAME, MODX_CORE_PATH . 'components/' . PKG_NAME_LOWER . '/model/' . PKG_NAME_LOWER . '/', []);
$modx->lexicon->load(PKG_NAME_LOWER . ':default');

$request = $modx->request;
$processorsPath = $modx->getOption('processorsPath', $module->config, MODX_CORE_PATH . 'processors/');
$request->handleRequest([
    'processors_path' => $processorsPath,
    'location' => '',
]);
