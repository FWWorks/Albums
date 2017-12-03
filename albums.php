<?php
date_default_timezone_set("Asia/Shanghai");
session_start();
@$my_id = $_SESSION["user_id"];
@$user_id = $_REQUEST["user_id"];
if($user_id == null)
    $user_id = $my_id;
if($user_id == null)
    exit(1);

include("db.php");
$db = new DB();
$user = $db->getUserById($user_id);
if($user == null)
    exit(1);
if($my_id == null)
    $me = null;
else if($my_id != $user_id)
    $me = $db->getUserById($my_id);
else
    $me = $user;

@$new_name = $_REQUEST["new_name"];
@$new_tags = $_REQUEST["new_tags"];
if($new_name != null && $me != null)
{
    $album = new Album();
    $album->name = $new_name;
    $album->date = time();
    $album->user = $me;
    $db->addAlbum($album);
    if($new_tags != null)
        $db->changeAlbumTags($album->id, explode(" ", $new_tags));
}

@$change_id = $_REQUEST["change_id"];
@$change_name = $_REQUEST["change_name"];
@$change_tags = $_REQUEST["change_tags"];
if($change_id != null && $me != null)
{
    if($change_name != null)
        $db->changeAlbumName($change_id, $change_name);
    if($change_tags != null)
        $db->changeAlbumTags($change_id, explode(" ", $change_tags));
}

@$remove_id = $_REQUEST["remove_id"];
if($remove_id != null && $me != null)
{
    $db->removeAlbum($remove_id);
}

$albums = $db->getAlbumsByUser($user);

?>

<html>
    <head>
		<meta charset="UTF-8">
		<title><?= $user->name ?>的相册</title>
		<script src="jquery.js"></script>
	</head>
    <body>
<?php
    if(count($albums) == 0)
    {
?>
        <p>暂无相册</p>
<?php
    }
    else
    {
?>
        <table border="1">
            <tr>
                <th>名称</th>
                <th>创建时间</th>
                <th>标签</th>
                <th>操作</th>
            </tr>
<?php
        foreach($albums as $album)
        {
            $tag_names = array();
            foreach($album->tags as $tag)
                array_push($tag_names, $tag->name);
            $tag_names = join(" ", $tag_names);
?>
            <tr>
                <td><a href="album.php?id=<?= $album->id ?>"><?= $album->name ?></td>
                <td><?= date("Y-m-d H:i", $album->date) ?></td>
                <td><?= $tag_names ?></td>
                <td>
                    <input type="button" value="修改名称" onclick="on_change_name(<?= $album->id ?>, '<?= $album->name ?>')">
                    <input type="button" value="修改标签" onclick="on_change_tags(<?= $album->id ?>, '<?= $tag_names ?>')">
                    <input type="button" value="删除" onclick="on_remove(<?= $album->id ?>)">
                </td>
            </tr>
<?php
        }
    }
?>
        </table>
    
<?php
    if($user == $me)
    {
?>
        <form action="albums.php" method="post" id="form_new_album">
            <table>
                <caption>新建相册</caption>
                <tr>
                    <td>名称</td>
                    <td><input type="text" name="new_name" id="new_name"></td>
                </tr>
                <tr>
                    <td>标签（空格隔开）</td>
                    <td><input type="text" name="new_tags" id="new_tags"></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="button" value="新建" onclick="on_add_album()"></td>
                </tr>
            </table>
        </form>
        
        <form action="albums.php" method="post" id="form_change_album">
            <input type="hidden" value="" id="change_id" name="change_id">
            <input type="hidden" value="" id="change_name" name="change_name">
            <input type="hidden" value="" id="change_tags" name="change_tags">
        </form>
        
        <form action="albums.php" method="post" id="form_remove_album">
            <input type="hidden" value="" id="remove_id" name="remove_id">
        </form>
        
<?php
    }
?>
    </body>
    <script>
        function on_add_album()
        {
            var name = $("#new_name").val();
            if(name == "")
            {
                alert("相册名称不能为空！");
                return false;
            }
            $("#form_new_album").submit();
        }
        
        function on_change_name(id, name)
        {
            var new_name = prompt("相册名称：", name);
            if(new_name == null || new_name == name)
                return;
            $("#change_id").val(id);
            $("#change_name").val(new_name);
            $("#form_change_album").submit();
        }
        
        function on_change_tags(id, tags)
        {
            var new_tags = prompt("相册标签：", tags);
            if(new_tags == null || new_tags == tags)
                return;
            $("#change_id").val(id);
            $("#change_tags").val(new_tags);
            $("#form_change_album").submit();
        }
        
        function on_remove(id)
        {
            $("#remove_id").val(id);
            $("#form_remove_album").submit();
        }
    </script>
</html>
