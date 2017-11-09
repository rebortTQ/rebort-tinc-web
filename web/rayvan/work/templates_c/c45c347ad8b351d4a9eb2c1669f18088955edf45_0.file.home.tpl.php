<?php
/* Smarty version 3.1.30, created on 2016-12-21 06:00:16
  from "/opt/www/RayvanWeb/templates/home.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_585a1a7078f149_01883853',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c45c347ad8b351d4a9eb2c1669f18088955edf45' => 
    array (
      0 => '/opt/www/RayvanWeb/templates/home.tpl',
      1 => 1481609532,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_585a1a7078f149_01883853 (Smarty_Internal_Template $_smarty_tpl) {
?>
<HTML>
<HEAD>
<TITLE>首页</TITLE>
<meta charset="UTF-8">
</HEAD>
<BODY>
	<form action="/RayvanWebAuth.php" method="post" />
	User Name:<input type="text" name="user" /><br/><br/>
	Password: &nbsp;<input type="password" name="password" /><br/><br/>
	<input type="submit" name="action" value="login"/>
	<a href="/RayvanWebAuth.php?act=register_page">去注册</a>
	</form>
</BODY>
</HTML>
<?php }
}
