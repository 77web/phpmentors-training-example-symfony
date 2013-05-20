<?php

$_SERVER['KERNEL_DIR'] = __DIR__;
require_once $_SERVER['KERNEL_DIR'].'/bootstrap.php.cache';

\Phake::setClient(\Phake::CLIENT_PHPUNIT);