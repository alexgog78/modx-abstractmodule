<?php

if (!class_exists('abstractHandler')) {
    require_once dirname(dirname(__FILE__)) . '/abstracthandler.class.php';
}

abstract class abstractRequestHandler extends abstractHandler
{

}
