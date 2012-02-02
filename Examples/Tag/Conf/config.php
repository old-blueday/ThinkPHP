<?php
    if (!defined('THINK_PATH')) exit();
    $arr =  require '../config.php';
    $arr['APP_AUTOLOAD_PATH'] ='@.TagLib';
    return $arr;
?>