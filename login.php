<?php

@$name = $_REQUEST["name"];
@$password = $_REQUEST["password"];

if($name == null || $password == null)
	$error = null;
else
{
	include("db.php");
	$db = new DB();
    $user = $db->getUserByAccount($name, $password);
	if($user)
	{
        session_start();
        $_SESSION["user_id"] = $user->id;
		header('Location: index.php');
		exit(0);
	}
	else
		$error = "帐号或密码错误！";
}

?>

<html>
    <head>
		<meta charset="UTF-8">
		<title>登录</title>
		<script src="jquery.js"></script>
	</head>
    <body>
		<form action="login.php" method="post" id="form">
			<table>
				<tr>
					<td>用户名</td>
					<td><input type="text" name="name" id="name"></td>
				</tr>
				<tr>
					<td>密码</td>
					<td><input type="password" name="password" id="password"></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="button" value="登录" onclick="on_submit()"></td>
				</tr>
			</table>
		</form>
    </body>
	<script>
        
<?php

	if($error != null)
		echo "alert(\"$error\")";

?>

		function on_submit()
		{
			var name = $("#name").val();
			var password = $("#password").val();
			if(name == "")
			{
				alert("用户名不能为空！");
				return false;
			}
			if(password == "")
            {
				alert("密码不能为空！");
				return false;
			}
			$("#form").submit();
			return true;
		}
	</script>
</html>
