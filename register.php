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

<html lang="en" class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="UTF-8" />
        <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">  -->
        <title>Register</title>
        <script src="jquery.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta name="description" content="Login and Registration Form with HTML5 and CSS3" />
        <meta name="keywords" content="html5, css3, form, switch, animation, :target, pseudo-class" />
        <meta name="author" content="Codrops" />
        <link rel="shortcut icon" href="../favicon.ico"> 
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
        <link rel="stylesheet" type="text/css" href="css/style1.css" />
		<link rel="stylesheet" type="text/css" href="css/animate-custom.css" />
    </head>
    <body>
        <div class="container">
            <!-- Codrops top bar -->
            <div class="codrops-top">
                
                <span class="right">
                   
                </span>
                <div class="clr"></div>
            </div><!--/ Codrops top bar -->
            <header>
                <h1>Welcome to <span>iAlbum</span></h1>
				<nav class="codrops-demos">
					
				</nav>
            </header>
            <section>				
                <div id="container_demo" >
                    
                    <a class="hiddenanchor" id="toregister"></a>
                    <a class="hiddenanchor" id="tologin"></a>
                    <div id="wrapper">
                        <div id="login" class="animate form">
                            <form  action="register.php" autocomplete="on" method="post" id="form"> 
                                <h1>Register</h1> 
                                <p> 
                                    <label for="username" class="uname" data-icon="u" > Your email or username </label>
                                    <input type="text" name="name" id="name"/>
                                </p>
                                <p> 
                                    <label for="password" class="youpasswd" data-icon="p"> Your password </label>
                                    <input type="password" name="password" id="password" /> 
                                </p>
                                <p> 
                                    <label for="password" class="youpasswd" data-icon="p"> Confirm password </label>
                                    <input type="password" id="password2" /> 
                                </p>
                                
                                <p class="login button"> 
                                    <input type="button" value="Register" onclick="on_submit()"/> 
								</p>
                                
                            </form>
                        </div>
						
                    </div>
                </div>  
            </section>
        </div>
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
</html> -->





<!-- <html>
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

//	if($error != null)
//		echo "alert(\"$error\")";

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
</html> -->
