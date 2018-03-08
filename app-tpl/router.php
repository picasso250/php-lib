<?php

use Lib\Router;
use Lib\Request;
use Lib\Response;
use Model\Question;
use Model\Answer;
use Model\User;

Router::add('#^/$#',function () {
  include VIEW_ROOT."/index.html";
});
