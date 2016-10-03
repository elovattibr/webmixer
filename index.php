<?php       

define('ROOT_FOLDER', dirname(__FILE__));

chdir(ROOT_FOLDER);

//Require the STD so the system can autoload itself
require_once 'system/std.php';
        
//Init and run just like zend
Runtime\App::init(require 'config/application.php')->run();