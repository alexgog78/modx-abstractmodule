<?php

define('MODX_API_MODE', true);

/** @noinspection PhpIncludeInspection */
require_once 'config.core.php';
/** @noinspection PhpIncludeInspection */
require_once 'run.class.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';


//$command = new runCommand($modx, $argv);
array_shift($argv);
if (empty($argv)) {
    die('Empty action' . PHP_EOL);
}
$command = new Run(array_shift($argv), $argv);
$command->run();
exit();
