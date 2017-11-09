<?php
/* Smarty version 3.1.30, created on 2016-12-22 02:58:28
  from "/opt/web/rayvan/www/tpl/register.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_585b4154c01843_91652603',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '291bd4e3f5b358f086da436ff63632adf49db691' => 
    array (
      0 => '/opt/web/rayvan/www/tpl/register.tpl',
      1 => 1482372438,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_585b4154c01843_91652603 (Smarty_Internal_Template $_smarty_tpl) {
?>
<HTML>
<HEAD>
<TITLE>用户注册</TITLE>
<meta charset="UTF-8">
</HEAD>
<BODY>
	<form action="/cgi-bin/account.php" method="post" />
	User Name:<input type="text" name="username" /><br/><br/>
	Password: <input type="text" name="password" /><br/><br/>
	Auth Code:<input type="text" name="authCode" /><br/><br/>
	<input type="submit" name="action" value="register"/>
	</form>
	<form action="/cgi-bin/account.php" method="post" />
	Send Number:<input type="text" name="username" /><br/><br/>
	<input type="submit" name="action" value="getAuthCode"/>
	</form>
</BODY>
</HTML>
<?php }
}
