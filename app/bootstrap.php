<?php
//Config
require_once '../app/config/config.php';

//Autoload core libraries
spl_autoload_register(function($className){
    require_once 'libraries/'.$className.'.php';
});