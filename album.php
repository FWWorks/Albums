<?php

@$id = $_REQUEST["id"];
if($id == null)
	exit(1);

include("db.php");
$db = new DB();

session_start();
@$my_id = $_SESSION["user_id"];
if($my_id == null)
	$me = null;
else
	$me = $db->getUserById($my_id);

$album = $db->getAlbumById($id);
if($album == null)
	exit(1);

@$add_photo_name = $_REQUEST["add_file_name"];
@$add_photo_tags = $_REQUEST["add_photo_tags"];
if($add_photo_name != null && $me != null)
{
	$photo = new Photo();
	$photo->file_name = $add_photo_name;
	$photo->album = $album;
	$db->addPhoto($photo);
	if($add_photo_tags != null)
		$db->changePhotoTags($photo->id, explode(" ", $add_photo_tags));
}

@$delete_photo_id = $_REQUEST["delete_photo_id"];
if($delete_photo_id != null && $me != null)
{
	$db->removePhoto($delete_photo_id);
}

@$change_photo_tags_id = $_REQUEST["change_photo_tags_id"];
@$change_photo_tags = $_REQUEST["change_photo_tags"];
if($change_photo_tags_id != null && $me != null)
{
	if($change_photo_tags != null)
		$db->changePhotoTags($change_photo_tags_id, explode(" ", $change_photo_tags));
}

$photos = $db->getPhotosByAlbum($album);

@$new_photo = $_FILES["new_photo"];
if($new_photo != null)
{
	$file_type = $new_photo["type"];
	if($file_type == "image/png")
		$file_type = "png";
	else if($file_type == "image/jpeg")
		$file_type = "jpg";
	else
		exit(1);
	$file_name = md5_file($new_photo["tmp_name"]);
	$file_path = "photos/$file_name.$file_type";
	if(!file_exists($file_path))
		move_uploaded_file($new_photo["tmp_name"], $file_path);
}

?>

<html>
    <head>
		<meta charset="UTF-8">
		<title><?= $album->user->name ?>的相册《<?= $album->name ?>》</title>
		<script src="jquery.js"></script>
	</head>
    <body>
<?php
	if($me != null && $me->id == $album->user->id)
	{
?>
		<form action="album.php?id=<?= $id?>" method="post" id="form_new_photo" enctype="multipart/form-data" <?php if($new_photo != null) echo "style='display:none'"; ?>>
			<span>上传图片</span>
			<span><input type="button" value="选择照片" onclick="on_to_select_photo()"></span>
			<input type="file" id="new_photo" name="new_photo" accept="image/png, image/jpeg" style="display:none" onchange="on_select_photo()">
		</form>
		
		<form action="album.php?id=<?= $id?>" method="post" id="form_add_photo" <?php if($new_photo == null) echo "style='display:none'";?>>
			<p>预览：</p>
			<p><img src="<?= $file_path ?>"></p>
			<p>标签（用空格隔开）：<input type="text" value="" id="add_photo_tags" name="add_photo_tags"></p>
			<p>
				<input type="button" value="加入相册" onclick="on_add_photo()">
				<input type="button" value="取消" onclick="on_cancel_photo()">
			</p>
			<input type="hidden" id="add_file_name" name="add_file_name" value="<?= $file_path ?>">
		</form>
		
		<form action="album.php?id=<?= $id?>" method="post" id="form_delete_photo">
			<input type="hidden" id="delete_photo_id" name="delete_photo_id">
		</form>
		
		<form action="album.php?id=<?= $id?>" method="post" id="form_change_photo_tags">
			<input type="hidden" id="change_photo_tags_id" name="change_photo_tags_id">
			<input type="hidden" id="change_photo_tags" name="change_photo_tags">
		</form>
<?php
	}
	
	if(count($photos) == 0)
	{
?>
		<p>暂无照片</p>
<?php
	}
	else
	{
?>
		<table border="1">
			<tr>
				<th>图片</th><th>标签</th><th>操作</th>
			</tr>
<?php
		foreach($photos as $photo)
		{
			$tag_names = array();
            foreach($photo->tags as $tag)
                array_push($tag_names, $tag->name);
            $tag_names = join(" ", $tag_names);
?>
			<tr>
				<td><img src="<?= $photo->file_name ?>"></td>
				<td><?= $tag_names ?></td>
				<td>
					<input type="button" value="删除照片" onclick="on_delete_photo(<?= $photo->id ?>)">
					<input type="button" value="修改标签" onclick="on_chage_tags(<?= $photo->id ?>, '<?= $tag_names ?>')">
				</td>
			</tr>
<?php
		}
?>
		</table>
<?php
	}
?>
	</body>
	<script>
		function on_to_select_photo()
		{
			$("#new_photo").click();
		}
		
		function on_select_photo()
		{
			var files = document.getElementById("new_photo").files;
			if(files != null && files.length > 0)
			{
				$("#form_new_photo").submit();
			}
		}
		
		function on_add_photo()
		{
			$("#form_add_photo").submit();
		}
		
		function on_cancel_photo()
		{
			$("#form_add_photo").hide();
			$("#form_new_photo").show();
		}
		
		function on_delete_photo(id)
		{
			$("#delete_photo_id").val(id);
			$("#form_delete_photo").submit();
		}
		
		function on_chage_tags(id, tags)
		{
            var new_tags = prompt("照片标签：", tags);
            if(new_tags == null || new_tags == tags)
                return;
            $("#change_photo_tags_id").val(id);
            $("#change_photo_tags").val(new_tags);
            $("#form_change_photo_tags").submit();
		}
	</script>
</html>
