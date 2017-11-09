<?php
define('APP_NAME', 'rayvan');
define('WEB_ROOT', '/opt/rayvan/web/rayvan');
define('WEB_RAYVAN_LOG_FILE', '/var/log/rayvan/web.log');
define('WEB_APP_DEBUG_LEVEL', 6);

define('SMARTY_TEMPLATE_DIR', WEB_ROOT.'/www/tpl/');
define('SMARTY_COMPILE_DIR', WEB_ROOT.'/work/templates_c');
define('SMARTY_CONFIG_DIR', WEB_ROOT.'/work/config');
define('SMARTY_CACHE_DIR', WEB_ROOT.'/work/cache');
define('CACHING',false);
?>
