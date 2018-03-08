<?php
/**
 * @file    index
 * @author  picasso250 <cumt.xiaochi@gmail.com>
 * app logic
 */

use Lib\Router;
use Lib\Container;
use Lib\EnvConfig;
use Lib\DirAutoLoad;
use Lib\BaseModel;

define('URL_ROOT', '/');
define('SRC_ROOT', dirname(__DIR__));

require SRC_ROOT.'/lib/lib.php';

autoload_dir("Lib", SRC_ROOT.'/lib');

require SRC_ROOT.'/logic.php';

session_start();

define('CONFIG_ROOT', SRC_ROOT.'/config');
define('VIEW_ROOT', SRC_ROOT.'/view');

autoload_dir("Model", SRC_ROOT."/model");

env_load(SRC_ROOT);

Container::set('config', $config);
Container::set('db', function () use ($config) {
	$dbc = $config['db'];
	$dbConnection = new PDO($dbc['dsn'], $dbc['username'], $dbc['password']);
	$dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbConnection;
});

include SRC_ROOT.'/routers.php';

if (!Router::run()) {
	exit('page 404');
}