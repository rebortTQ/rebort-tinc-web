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
	</form>
</BODY>
</HTML>
