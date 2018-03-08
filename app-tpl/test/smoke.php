<?php

define('SRC_ROOT', dirname(__DIR__));
define('CONFIG_ROOT', SRC_ROOT.'/config');

require SRC_ROOT.'/vendor/autoload.php';
require SRC_ROOT.'/lib/autoload.php';

use Testify\Testify;
use Lib\Router;
use Lib\Service;
use Lib\EnvConfig;
use Lib\DirAutoLoad;
use Lib\BaseModel;
use Lib\MysqlUtil;
use Model\User;
use Model\Question;

DirAutoLoad::loadDir("Model", SRC_ROOT."/model");

$config = EnvConfig::load(CONFIG_ROOT);
Service::set('config', $config);
Service::register('db', function () use ($config) {
	$dbc = $config['db'];
	$dbConnection = new PDO($dbc['dsn'], $dbc['username'], $dbc['password']);
	$dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbConnection;
});
BaseModel::$db_getter = function () {
	return Service::get('db');
};
Service::register('user', function () {
	$user_id = 2;//user_id();
	return $user_id ? User::getById($user_id) : null;
});

User::add([
	"uid" => "1",
	"name" => "test",
	"say" => "",
	"email" => "test@test.com",
	"phone" => "110",
	"id_" => "3333",
	"register_time" => MysqlUtil::timestamp()
]);

$tf = new Testify("MyCalc Test Suite");

$tf->beforeEach(function($tf) {
});

$tf->test("Testing the Question::check_dup() method", function($tf) {
	$id=Question::add(array("title" => "lalala", "detail" => ""));
  $row = Question::check_dup("lala");
  $tf->assertEquals($row['title'], "lalala");
  Question::delById($id);
});

$tf();

User::delById("2");
