<?php
require_once('inc/config.inc.php');
require_once(RAYVAN_LIBS.'/classes/SmartySetup.class.php');
require_once(RAYVAN_LIBS."/classes/rayvan/RayvanFunctions.class.php");
$REDIS_conf = array();
$func = new RayvanFunctions();
$config_path = $func->get_app_config_path();
$config = $config_path.'/app.conf';

$func->xhsjweb_conf_parse($config, $REDIS_conf);

ini_set("session.save_handler", "redis");
ini_set("session.save_path", "tcp://".$REDIS_conf['redis']['host'].":".$REDIS_conf['redis']['port']."?auth=".$REDIS_conf['redis']['password']);
session_start();

$smarty = new SmartySetup();
$smarty->display('home.tpl');
?>
