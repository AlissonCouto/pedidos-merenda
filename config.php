<?php
/**
 * Created by PhpStorm.
 * User: alissoncouto
 * Date: 04/09/2019
 * Time: 18:51
 */

spl_autoload_register(function($class_name){

    $filename = "model/dao" . DIRECTORY_SEPARATOR . $class_name . ".php";

    if(file_exists(($filename))){
        require_once($filename);
    }
});

spl_autoload_register(function($class_name){

    $filename = "model" . DIRECTORY_SEPARATOR . $class_name . ".php";

    if(file_exists(($filename))){
        require_once($filename);
    }
});

spl_autoload_register(function($class_name){

    $filename = ".." . DIRECTORY_SEPARATOR . $class_name . ".php";

    if(file_exists(($filename))){
        require_once($filename);
    }
});
