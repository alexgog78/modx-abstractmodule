<?php

require_once dirname(__FILE__) . '/config.inc.php';
require_once dirname(dirname(__FILE__)) . '/config.core.php';
require_once MODX_CORE_PATH . 'components/abstractmodule/cli/abstractbuildtransport.class.php';

class BuildTransport extends abstractBuildTransport
{
    /** @var bool */
    protected $coreFiles = true;

    /** @var bool */
    protected $assetsFiles = true;
}

array_shift($argv);
$build = new BuildTransport($argv);
$build->run();
exit();
