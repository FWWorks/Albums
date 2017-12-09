<?php
date_default_timezone_set("Asia/Shanghai");
session_start();
@$my_id = $_SESSION["user_id"];
?>

<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<title>iAlbum</title>
<meta name="keywords" content="iAlbum" />
<meta name="description" content="" />
<link rel="stylesheet" href="css/index.css"/>
<link rel="stylesheet" href="css/style.css"/>
<script type="text/javascript" src="js/jquery1.42.min.js"></script>
<script type="text/javascript" src="js/jquery.SuperSlide.2.1.1.js"></script>
<!--[if lt IE 9]>
<script src="js/html5.js"></script>
<![endif]-->
</head>

<body>
    <!--header start-->
    <div id="header">
      <h1>iAlbum</h1>
      <h1><?= $user_id ?>aaa</h1>
      <p>Confidence and Smile</p>    
    </div>
     <!--header end-->
    <!--nav-->
     <div id="nav">
        <ul>
         <li><a href="index.html">Moments</a></li>
         <li><a href="albums.php">Photos</a></li>
         <li><a href="shuo.html">Activities</a></li>
         <li><a href="riji.html">Me</a></li>
         
         <div class="clear"></div>
        </ul>
      </div>
       <!--nav end-->
    <!--content start-->
    <div id="content">
         <!--left-->
         <div class="left" id="c_left">
           <div class="s_tuijian">

           <h2>Recommendation</h2>
           </div>
          <div class="content_text">
           <!--wz-->
           <div class="wz">
            <h3><a href="#" title="标题1">标题1</a></h3>
             <dl>
               <dt><img src="images/s.jpg" width="200"  height="123" alt=""></dt>
               <dd>
                 <p class="dd_text_1">描述1</p>
               <p class="dd_text_2">
               <span class="left author">用户名</span><span class="left sj">时间</span>
               <span class="left yd"><a href="#" title="view">view</a>
               </span>
                <div class="clear"></div>
               </p>
               </dd>
               <div class="clear"></div>
             </dl>
            </div>
           <!--wz end-->
            <!--wz-->
           <div class="wz">
            <h3><a href="#" title="标题1">标题1</a></h3>
             <dl>
               <dt><img src="images/s.jpg" width="200"  height="123" alt=""></dt>
               <dd>
                 <p class="dd_text_1">描述1</p>
               <p class="dd_text_2">
               <span class="left author">用户名</span><span class="left sj">时间</span>
               <span class="left yd"><a href="#" title="view">view</a>
               </span>
                <div class="clear"></div>
               </p>
               </dd>
               <div class="clear"></div>
             </dl>
            </div>
           <!--wz end-->
            <!--wz-->
           <div class="wz">
            <h3><a href="#" title="标题1">标题1</a></h3>
             <dl>
               <dt><img src="images/s.jpg" width="200"  height="123" alt=""></dt>
               <dd>
                 <p class="dd_text_1">描述1</p>
               <p class="dd_text_2">
               <span class="left author">用户名</span><span class="left sj">时间</span>
               <span class="left yd"><a href="#" title="view">view</a>
               </span>
                <div class="clear"></div>
               </p>
               </dd>
               <div class="clear"></div>
             </dl>
            </div>
           <!--wz end-->
           <!--wz-->
           <div class="wz">
            <h3><a href="#" title="标题1">标题1</a></h3>
             <dl>
               <dt><img src="images/s.jpg" width="200"  height="123" alt=""></dt>
               <dd>
                 <p class="dd_text_1">描述1</p>
               <p class="dd_text_2">
               <span class="left author">用户名</span><span class="left sj">时间</span>
               <span class="left yd"><a href="#" title="view">view</a>
               </span>
                <div class="clear"></div>
               </p>
               </dd>
               <div class="clear"></div>
             </dl>
            </div>
           <!--wz end-->
            <!--wz-->
           <div class="wz">
            <h3><a href="#" title="标题1">标题1</a></h3>
             <dl>
               <dt><img src="images/s.jpg" width="200"  height="123" alt=""></dt>
               <dd>
                 <p class="dd_text_1">描述1</p>
               <p class="dd_text_2">
               <span class="left author">用户名</span><span class="left sj">时间</span>
               <span class="left yd"><a href="#" title="view">view</a>
               </span>
                <div class="clear"></div>
               </p>
               </dd>
               <div class="clear"></div>
             </dl>
            </div>
           <!--wz end-->
              
                             
           </div>
         </div>
         <!--left end-->
         <!--right-->
         <div class="right" id="c_right">
          <div class="s_about">
           <img src="images/me.jpg" width="230" height="230" alt=""/>
           <p>用户名：xx</p>
           <p>简介：</p>
           <p>
           <br></br>
           <div class="clear"></div>
           </p>
          </div>
          <!--栏目分类-->
           <div class="lanmubox">
              <div class="hd">
               <ul><li>关注者动态</li><li>粉丝动态</li><li class="hd_3">我的动态</li></ul>
              </div>
              <div class="bd">
                <ul>
					
					
					
				</ul>
                 <ul>
					
					
				</ul>
                 <ul>
					
					
				</ul>
                 
                
              </div>
           </div>
           <!--end-->
           
         </div>
         <!--right end-->
         <div class="clear"></div>
    </div>
    <!--content end-->
    <!--footer start-->
    <div id="footer">
     
    </div>
    <!--footer end-->
    <script type="text/javascript">jQuery(".lanmubox").slide({easing:"easeOutBounce",delayTime:400});</script>
    <script  type="text/javascript" src="js/nav.js"></script>
</body>
</html>