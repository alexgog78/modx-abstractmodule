<?php

require_once dirname(__FILE__) . '/config.inc.php';

class BuildTransport extends abstractBuildTransport
{
    /** @var bool */
    protected $coreFiles = true;

    /** @var bool */
    protected $assetsFiles = true;
}
