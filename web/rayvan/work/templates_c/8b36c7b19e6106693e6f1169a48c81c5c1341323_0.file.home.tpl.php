<?php
/* Smarty version 3.1.30, created on 2016-12-22 02:07:23
  from "/opt/web/rayvan/www/tpl/home.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_585b355bbc17f7_84902121',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8b36c7b19e6106693e6f1169a48c81c5c1341323' => 
    array (
      0 => '/opt/web/rayvan/www/tpl/home.tpl',
      1 => 1482372412,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_585b355bbc17f7_84902121 (Smarty_Internal_Template $_smarty_tpl) {
?>
<HTML>
<HEAD>
<TITLE>首页</TITLE>
<meta charset="UTF-8">
</HEAD>
<BODY>
	<form action="/cgi-bin/account.php" method="post" />
	User Name:<input type="text" name="user" /><br/><br/>
	Password: &nbsp;<input type="password" name="password" /><br/><br/>
	<input type="submit" name="action" value="login"/>
	<a href="/cgi-bin/account.php?act=register_page">去注册</a>
	</form>
</BODY>
</HTML>
<?php }
}
