<?php

$prefix = '';

$_lang[$prefix] = 'AbstractModule';
$_lang[$prefix . '.management'] = 'AbstractModule for building MODX components.';

$files = scandir(dirname(__FILE__));
foreach ($files as $file) {
    if (strpos($file, '.inc.php')) {
        @include_once $file;
    }
}
