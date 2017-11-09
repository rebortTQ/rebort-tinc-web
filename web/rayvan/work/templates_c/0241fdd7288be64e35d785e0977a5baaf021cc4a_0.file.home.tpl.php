<?php
/* Smarty version 3.1.30, created on 2017-06-26 11:31:33
  from "/opt/rayvan/web/rayvan/www/tpl/home.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_59508015c22b95_09352728',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0241fdd7288be64e35d785e0977a5baaf021cc4a' => 
    array (
      0 => '/opt/rayvan/web/rayvan/www/tpl/home.tpl',
      1 => 1498445756,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_59508015c22b95_09352728 (Smarty_Internal_Template $_smarty_tpl) {
?>
<HTML>
<HEAD>
<TITLE>首页</TITLE>
<meta charset="UTF-8">
</HEAD>
<BODY>
	<form action="/cgi-bin/account.php" method="post" />
        <input type="hidden" name="version" value="1.2.1"/>
        <input type="hidden" name="module" value="account"/>
        <input type="hidden" name="login_location" value="web-llq"/>
	User Name:<input type="text" name="account" /><br/><br/>
	Password: &nbsp;<input type="password" name="password" /><br/><br/>
	<input type="submit" name="action" value="login"/>
	<a href="/cgi-bin/account.php?act=register_page">去注册</a>
	</form>
</BODY>
</HTML>
<?php }
}
