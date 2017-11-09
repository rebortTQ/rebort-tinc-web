<?php
/* Smarty version 3.1.30, created on 2017-06-29 10:13:00
  from "/opt/rayvan/web/rayvan/www/tpl/account_password_reset.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5954622c28a4a5_59014681',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f7cb4962ab23a133fde6fa0f74373f14b4b933f7' => 
    array (
      0 => '/opt/rayvan/web/rayvan/www/tpl/account_password_reset.tpl',
      1 => 1498702284,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5954622c28a4a5_59014681 (Smarty_Internal_Template $_smarty_tpl) {
?>
<HTML>
<HEAD>
<TITLE></TITLE>
<meta charset="UTF-8">
<?php echo '<script'; ?>
 language="javascript" type="text/javascript" src="/resources/js/hex_md5.js" ><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript">
        function check(){
                var new_password = document.getElementById("new_password");
                var confirm_password = document.getElementById("confirm_password");
                var data_new_password = document.getElementById("data_new_password");

                if(new_password.value.length < 6){
                        alert("密码长度6~32");
                        return false;
                }
                if(new_password.value != confirm_password.value){
                        alert("确认密码不正确");
                        return false;
                }
                if(data_new_password == null){
                        alert("null");
                }
                data_new_password.value = hex_md5(new_password.value);
                return true;
        }
<?php echo '</script'; ?>
>
</HEAD>
<BODY>
        新密码:&nbsp;<input type="password" name="new_password" id="new_password" /><br/><br/>
        确认密码:&nbsp;<input type="password" name="confirm_password" id="confirm_password" /><br/><br/>
        <form action="/cgi-bin/account.php" method="post" onSubmit="return check();"/>
                <input type="hidden" name="version" value="<?php echo $_smarty_tpl->tpl_vars['version']->value;?>
"/>
                <input type="hidden" name="module" value="<?php echo $_smarty_tpl->tpl_vars['module']->value;?>
"/>
                <input type="hidden" name="token" value="<?php echo $_smarty_tpl->tpl_vars['token']->value;?>
"/>
                <input type="hidden" name="data_new_password" id="data_new_password" value=""/>
                <input type="hidden" name="action" value="retrievePassword_3">
                <input type="submit" name="button_name" value="重置密码">
        </form>
</BODY>
</HTML>
<?php }
}
