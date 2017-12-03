<?php

@$name = $_REQUEST["name"];
@$password = $_REQUEST["password"];

if($name == null || $password == null)
	$error = null;
else
{
	include("db.php");
	$user = new User();
	$user->name = $name;
	$user->password = $password;
	$db = new DB();
	if($db->addUser($user))
	{
		header("Location: login.php");
		exit(0);
	}
	else
		$error = "用户名已存在！";
}

?>

<html>
	<head>
		<meta charset="UTF-8">
		<title>注册</title>
		<script src="jquery.js"></script>
	</head>
	<body>
		<form action="register.php" method="post" id="form">
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
					<td>确认密码</td>
					<td><input type="password" id="password2"></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="button" value="注册" onclick="on_submit()"></td>
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
			var password2 = $("#password2").val();
			if(name == "")
			{
				alert("用户名不能为空！");
				return false;
			}
			if(password.length <6)
			{
				alert("密码长度必须大于等于6位！");
				return false;
			}
			if(password != password2)
			{
				alert("两次密码不一致！");
				return false;
			}
			$("#form").submit();
			return true;
		}
	</script>
</html>
