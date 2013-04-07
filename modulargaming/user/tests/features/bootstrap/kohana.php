<?php

// Init Kohana
defined('APPPATH') ?: define('APPPATH', 'application/');
defined('MODPATH') ?: define('MODPATH', 'modules/');
defined('SYSPATH') ?: define('SYSPATH', 'system/');
defined('MGPATH')  ?: define('MGPATH', 'modulargaming/');
defined('EXT') ?: define('EXT', '.php');

require_once APPPATH.'bootstrap.php';

Database::$default = 'test';