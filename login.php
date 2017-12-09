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

 <html lang="en" class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="UTF-8" />
        <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">  -->
        <title>Login</title>
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
                            <form  action="login.php" autocomplete="on" method="post" id="form"> 
                                <h1>Log in</h1> 
                                <p> 
                                    <label for="username" class="uname" data-icon="u" > Your email or username </label>
                                    <input type="text" name="name" id="name"/>
                                </p>
                                <p> 
                                    <label for="password" class="youpasswd" data-icon="p"> Your password </label>
                                    <input type="password" name="password" id="password" /> 
                                </p>
                                <p class="keeplogin"> 
									<input type="checkbox" name="loginkeeping" id="loginkeeping" value="loginkeeping" /> 
									<label for="loginkeeping">Keep me logged in</label>
								</p>
                                <p class="login button"> 
                                    <input type="submit" value="Login" onclick="on_submit()"/> 
								</p>
                                <p class="change_link">
									Not a member yet ?
									<a href="register.php" class="to_register">Join us</a>
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

<!-- <html>
    <head>
		<meta charset="UTF-8">
		<title>登录</title>
		<script src="jquery.js"></script>
	</head>
    <body background="img1.JPG">
		<form action="login.php" method="post" id="form">
			<table>
				<tr>
					<td>用户名 </td>
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

//	if($error != null)
//		echo "alert(\"$error\")";

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
 -->