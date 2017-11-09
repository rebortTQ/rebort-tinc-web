<?php
require_once('inc/config.inc.php');
require_once(RAYVAN_LIBS."/classes/rayvan/provider/Database/RedisHandle.class.php");

Class RedisConnect extends RedisHandle{
        function __construct()
        {
                $DB_conf = array();
		$func = new RayvanFunctions();
		$config_path = $func->get_app_config_path();
		$config = $config_path.'/app.conf';

		$func->xhsjweb_conf_parse($config, $DB_conf);

                parent::__construct($DB_conf['redis']['host'], $DB_conf['redis']['port'], $DB_conf['redis']['password']);
        }

        function __destruct()
        {
                parent::__destruct();
        }
}
$redis = new RedisConnect();
?>
