<?php

if (!$this->loadClass('getlist', MODX_PROCESSORS_PATH . 'security/user/', true, true)) {
    return false;
}

abstract class amUserGetListProcessor extends modUserGetListProcessor
{

}
