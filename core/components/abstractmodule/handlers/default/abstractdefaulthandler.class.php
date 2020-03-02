<?php

if (!class_exists('AbstractHandler')) {
    require_once dirname(dirname(__FILE__)) . '/abstracthandler.class.php';
}

abstract class AbstractDefaultHandler extends AbstractHandler
{

}
