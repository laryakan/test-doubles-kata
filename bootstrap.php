<?php
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

if (getenv('COMPOSER_VENDOR_DIR')) {
    require_once getenv('COMPOSER_VENDOR_DIR') . '/autoload.php';
} else {
    require_once APP_ROOT . '/vendor/autoload.php';
}
