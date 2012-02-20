<?php

/**
 * +---------------------------------------------------
 * | SAE本地开发环境， 模拟sae服务
 * +---------------------------------------------------
 * @author luofei614<www.3g4k.com>
 */
@(ini_set('post_max_size', '10M')); // sae下最大上传文件为10M
@(ini_set('upload_max_filesize', '10M'));
$sae_config = include(THINK_PATH.'Sae/SaeImit/config.php');//读取配置文件
include_once THINK_PATH.'Sae/SaeImit/defines.php';
include_once THINK_PATH.'Sae/SaeImit/sae_functions.php';
include_once THINK_PATH.'Sae/SaeImit/imit_functions.php';
include_once THINK_PATH.'Sae/SaeImit/Lang.php';
spl_autoload_register('sae_auto_load');
function sae_auto_load($class){
    $files=array(
    'SaeObject'=>THINK_PATH.'Sae/SaeImit/SaeObject.class.php',
    'SaeCounter'=> THINK_PATH.'Sae/SaeImit/SaeCounter.class.php',
    'SaeRank'=>THINK_PATH.'Sae/SaeImit/SaeRank.class.php',
    'SaeTaskQueue'=>THINK_PATH.'Sae/SaeImit/SaeTaskQueue.class.php',
    'SaeStorage'=>THINK_PATH.'Sae/SaeImit/SaeStorage.class.php',
    'SaeKVClient'=>THINK_PATH.'Sae/SaeImit/SaeKVClient.class.php',
    'Memcache'=>THINK_PATH.'Sae/SaeImit/Memcache.class.php',
    'CacheFile'=>THINK_PATH.'Lib/Driver/Cache/CacheFile.class.php',
    'SaeMail'=>THINK_PATH.'Sae/SaeImit/SaeMail.class.php',
    'SaeMysql'=>THINK_PATH.'Sae/SaeImit/SaeMysql.class.php',
    'ImitSqlite'=>THINK_PATH.'Sae/SaeImit/ImitSqlite.class.php',
     'SaeFetchurl'=>THINK_PATH.'Sae/SaeImit/SaeFetchurl.class.php',
     'SaeImage'=>THINK_PATH.'Sae/SaeImit/SaeImage.class.php'
    );
    if(isset($files[$class]))
        require $files[$class];
}
?>
