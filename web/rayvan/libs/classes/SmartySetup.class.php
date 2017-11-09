<?php
require_once('smarty/libs/Smarty.class.php');
require_once('inc/config.inc.php');
require_once(RAYVAN_LIBS.'/inc/config.inc.php');

class SmartySetup extends Smarty{
        function __construct(){
                parent::__construct();
                $this->setTemplateDir(SMARTY_TEMPLATE_DIR);
                $this->setCompileDir(SMARTY_COMPILE_DIR);
                $this->setConfigDir(SMARTY_CONFIG_DIR);
                $this->setCacheDir(SMARTY_CACHE_DIR);

                $this->caching = CACHING;
                $this->assign('APP_NAME', APP_NAME);
        }
};
?>
