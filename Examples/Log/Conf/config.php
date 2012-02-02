<?php
    if (!defined('THINK_PATH'))
        exit();
    $config = require '../config.php';
    $array = array(
        'LOG_RECORD' => true
    );
    return array_merge($config, $array);
?>