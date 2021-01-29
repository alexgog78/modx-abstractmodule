<?php

define('PKG_NAME', 'abstractModule');
define('PKG_NAME_LOWER', strtolower(PKG_NAME));

define('PKG_VERSION', '1.1.0');
define('PKG_RELEASE', 'beta');
define('PKG_PATH', MODX_CORE_PATH . 'components/' . PKG_NAME_LOWER . '/');

define('PKG_BUILD_PATH', __DIR__ . '/');
define('PKG_BUILD_TRANSPORT_PATH', PKG_BUILD_PATH . 'transport/');
