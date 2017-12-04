<?php

define("DB_PATH", "db.sqlite");
define("DB_INIT_SCRIPT_PATH", "db_init.sql");

class User
{
	public $id;
	public $name;
	public $password;
}

class Activity
{
	public $id;
	public $name;
	public $start_date;		// a integer, unix timstamp
	public $end_date;		// a integer, unix timstamp
	public $description;
	public $user;			// an instance of User, whose is the sponsor
}

class Tag
{
    public $id;
    public $name;
}

class Album
{
    public $id;
    public $name;
    public $date;
    public $tags;           // an array of instances of Tag
    public $user;           // an instance of User, whose is the owner
}

class Photo
{
	public $id;
	public $file_name;
	public $album;			// an instance of Album
	public $tags;			// an array of instance of Tag
}

class DB
{
	
	public $sqlite;
	
	function __construct()
	{
		if(file_exists(DB_PATH))
			$this->sqlite = new SQLite3(DB_PATH);
		else
		{
			$flag = SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE;
			$this->sqlite = new SQLite3(DB_PATH, $flag);
			$script = file_get_contents(DB_INIT_SCRIPT_PATH);
			$this->sqlite->exec($script);
		}
		
	}
	
	function __destruct()
	{
		$this->sqlite->close();
	}
	
	/*
	 * add a new user into database
	 * $user->name and $user->password are needed, but $user->id is ignored
	 * if the user name already exists, return false
	 * else return true, and $user->id is filled
	 */
	function addUser($user)
	{
		$sql = "SELECT * FROM t_user where f_name = :name";
		$stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":name", $user->name);
		$result = $stmt->execute();
		$row = $result->fetchArray(SQLITE3_ASSOC);
		if($row != false)
			return false;
		$sql = "INSERT INTO t_user (f_name, f_password) VALUES (:name, :password)";
		$stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":name", $user->name);
		$stmt->bindValue(":password", $user->password);
		$stmt->execute();
		$user->id = $this->sqlite->lastInsertRowID();
		return true;
	}
	
	/*
	 * get a user with the given id from database
	 * return null is no such user
	 * return an instance of User if found
	 */
	function getUserById($user_id)
	{
		$sql = "SELECT * FROM t_user WHERE f_id = :id";
		$stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":id", $user_id);
		$result = $stmt->execute();
		$row = $result->fetchArray(SQLITE3_ASSOC);
		if($row == false)
			return null;
		$user = new User();
		$user->id = $row["f_id"];
		$user->name = $row["f_name"];
		$user->password = $row["f_password"];
		return $user;
	}
	
	/*
	 * get a user with the given name and password
	 * return null is no such user
	 * return an instance of User if found
	 */
	function getUserByAccount($user_name, $user_password)
	{
		$sql = "SELECT * FROM t_user where f_name = :name and f_password = :password";
		$stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":name", $user_name);
		$stmt->bindValue(":password", $user_password);
		$result = $stmt->execute();
		$row = $result->fetchArray(SQLITE3_ASSOC);
		if($row == false)
			return null;
		$user = new User();
		$user->id = $row["f_id"];
		$user->name = $row["f_name"];
		$user->password = $row["f_password"];
		return $user;
	}
	
	/*
	 * change the password of a user with the given id
	 * return true if succeed, false otherwise
	 */
	function changeUserPassword($user_id, $user_password)
	{
		$sql = "UPDATE t_user SET f_password = :password WHERE f_id = :id";
		$stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":password", $user_password);
		$stmt->bindValue(":id", $user_id);
		$stmt->execute();
		$changes = $this->sqlite->changes();
		return $changes == 1;
	}
	
	/*
	 * add an activity to datebase
	 * return void
	 */
	function addActivity($activity)
	{
		$sql = "INSERT INTO t_activity (f_name, f_start_date, f_end_date, f_description, f_user_id) VALUES (:name, :start_date, :end_date, :description, :user_id)";
		$stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":name", $activity->name);
		$stmt->bindValue(":start_date", $activity->start_date);
		$stmt->bindValue(":end_date", $activity->end_date);
		$stmt->bindValue(":description", $activity->description);
		$stmt->bindValue(":user_id", $activity->user->id);
		$stmt->execute();
		$activity->id = $this->sqlite->lastInsertRowID();
	}
	
	/*
	 * update a given activity
	 * $activity->name, $activity->start_date, $activity->end_date, $activity->description will be updated to datebase accoding to $activity->id
	 * but $activity->user is ignored
	 */
	function updateActivity($activity)
	{
		$sql = "UPDATE t_activity SET f_name = :name, f_start_date = :start_date, f_end_date = :end_date, f_description = :description WHERE f_id = :id";
		$stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":name", $activity->name);
		$stmt->bindValue(":start_date", $activity->start_date);
		$stmt->bindValue(":end_date", $activity->end_date);
		$stmt->bindValue(":description", $activity->description);
		$stmt->bindValue(":id", $activity->id);
		$stmt->execute();
		$changes = $this->sqlite->changes();
		return $changes == 1;
	}
	
	/*
	 * delete an activity with given id
	 * and all the 'join' relationship will be deleted
	 * return true if succeed, false otherwise
	 */
	function deleteActivity($activity_id)
	{
		$sql = "DELETE FROM t_join WHERE f_activity_id = :activity_id";
		$stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":activity_id", $activity_id);
		$stmt->execute();
		$sql = "DELETE FROM t_activity WHERE f_id = :id";
		$stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":id", $activity_id);
		$stmt->execute();
		$changes = $this->sqlite->changes();
		return $changes == 1;
	}
	
	/*
	 * a user join a activity
	 * return true if succeed, false otherwise
	 */
	function userJoinActivity($user_id, $activity_id)
	{
		$sql = "SELECT * FROM t_join WHERE f_user_id = :user_id and f_activity_id = :activity_id";
		$stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":user_id", $user_id);
		$stmt->bindValue(":activity_id", $activity_id);
		$result = $stmt->execute();
		$row = $result->fetchArray(SQLITE3_ASSOC);
		if($row != false)
			return true;
		$sql = "INSERT INTO t_join (f_user_id, f_activity_id) VALUES (:user_id, :activity_id)";
		$stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":user_id", $user_id);
		$stmt->bindValue(":activity_id", $activity_id);
		$stmt->execute();
		$changes = $this->sqlite->changes();
		return $changes == 1;
	}
	
	/*
	 * get an activity with a given id
	 * return null if no such activity, otherwise an instance of Activity
	 * and the field <user> is filled with an instance of User, whose is the sponsor
	 */
	function getActivityById($activity_id)
	{
		$sql = "SELECT * FROM t_activity WHERE f_id = :id";
		$stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":id", $activity_id);
		$result = $stmt->execute();
		$row = $result->fetchArray(SQLITE3_ASSOC);
		if($row == false)
			return null;
		$activity = new Activity();
		$activity->name = $row["f_name"];
		$activity->start_date = $row["f_start_date"];
		$activity->end_date = $row["f_end_date"];
		$activity->description = $row["f_description"];
		$user = $this->getUserById($row["f_user_id"]);
		assert($user != null);
		$activity->user = $user;
		return $activity;
	}
    
    /*
     * add a new album into DB
     * <album> is an instance of Album, with field <name>, <data> and <user> filled
     * And the album->id and album->tags is ignored.
     * return void, and album->id is field.
     */
    function addAlbum($album)
    {
        $sql = "INSERT INTO t_album (f_name, f_date, f_user_id) VALUES (:name, :date, :user_id)";
		$stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":name", $album->name);
		$stmt->bindValue(":date", $album->date);
		$stmt->bindValue(":user_id", $album->user->id);
		$stmt->execute();
		$album->id = $this->sqlite->lastInsertRowID();
    }
    
    /*
     * remove an ablum with the given id.
     * <album_id> is the id of the album
     * return true if succeed, false otherwise
     */
    function removeAlbum($album_id)
    {
        $sql = "DELETE FROM t_album WHERE f_id = :id";
        $stmt = $this->sqlite->prepare($sql);
        $stmt->bindValue(":id", $album_id);
        $stmt->execute();
        $changes = $this->sqlite->changes();
		return $changes == 1;
    }
    
    /*
     * get all of the given user's albums
     * <user> is an instance of User,
     * return an array containing all his/her albums, or an empty array.
     */
    function getAlbumsByUser($user)
    {
        $sql = "SELECT * FROM t_album WHERE f_user_id = :user_id";
		$stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":user_id", $user->id);
        $result = $stmt->execute();
        $all = array();
        while($row = $result->fetchArray(SQLITE3_ASSOC))
        {
            $album = new Album();
            $album->id = $row["f_id"];
            $album->name = $row["f_name"];
            $album->date = $row["f_date"];
            $album->tags = $this->getTagsByAlbumId($album->id);
            $album->user = $user;
            array_push($all, $album);
        }
        return $all;
    }
    
    /*
     * get all the tags attached to the given album,
     * <album_id> is the id of the album,
     * return an array containing all the Tag instance, or an empty array.
     */
    function getTagsByAlbumId($album_id)
    {
        $sql = "SELECT * FROM t_tag, t_tagging WHERE t_tagging.f_album_id = :album_id and t_tag.f_id = t_tagging.f_tag_id";
		$stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":album_id", $album_id);
        $result = $stmt->execute();
        $all = array();
        while($row = $result->fetchArray(SQLITE3_ASSOC))
        {
            $tag = new Tag();
            $tag->id = $row["f_id"];
            $tag->name = $row["f_name"];
            array_push($all, $tag);
        }
        return $all;
    }    

    /*
     * get an instance of Tag by the name of tag
     * return an instance of Tag, or null
     */
    function getTagByName($tag_name)
    {
        $sql = "SELECT * FROM t_tag WHERE f_name = :name";
        $stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":name", $tag_name);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        if($row == false)
            return null;
        $tag = new Tag();
        $tag->id = $row["f_id"];
        $tag->name = $row["f_name"];
        return $tag;
    }

    /*
     * add a tag to an album,
     * <album_id> is the id of the album, <tag_name> is a string,
     * return void
     */
    function addTagToAlbum($album_id, $tag_name)
    {
        $tag = $this->getTagByName($tag_name);
        if($tag)
            $tag_id = $tag->id;
        else
        {
            $sql = "INSERT INTO t_tag (f_name) VALUES (:name)";
            $stmt = $this->sqlite->prepare($sql);
            $stmt->bindValue(":name", $tag_name);
            $stmt->execute();
            $tag_id = $this->sqlite->lastInsertRowID();
        }
        $sql = "SELECT * FROM t_tagging WHERE f_tag_id = :tag_id and f_album_id = :album_id";
        $stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":tag_id", $tag_id);
        $stmt->bindValue(":album_id", $album_id);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        if($row !== false)
            return;
        $sql = "INSERT INTO t_tagging (f_tag_id, f_album_id) VALUES (:tag_id, :album_id)";
        $stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":tag_id", $tag_id);
        $stmt->bindValue(":album_id", $album_id);
        $stmt->execute();
    }
    
    /*
     * remove a tag from the album
     */
    function removeTagFromAlbum($album_id, $tag_name)
    {
        $tag = $this->getTagByName($tag_name);
        if($tag == null)
            return;
        $sql = "DELETE FROM t_tagging WHERE f_tag_id = :tag_id and f_album_id = :album_id";
        $stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":tag_id", $tag->id);
        $stmt->bindValue(":album_id", $album_id);
        $result = $stmt->execute();
    }
    
    /*
     * change the name of a given album,
     * <album_id> is the id of the album, <album_name> is the new name,
     * return true if succeed, false otherwise
     */
    function changeAlbumName($album_id, $album_name)
    {
        $sql = "UPDATE t_album SET f_name = :name WHERE f_id = :id";
        $stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":name", $album_name);
        $stmt->bindValue(":id", $album_id);
        $stmt->execute();
        $changes = $this->sqlite->changes();
		return $changes == 1;
    }
    
    /*
     * change the tags of a given album,
     * <album_id> is the id of the album, <tag_names> is an array of string of the new tags,
     * return void
     */
    function changeAlbumTags($album_id, $tag_names)
    {
        $old_tag_names = array();
        foreach($this->getTagsByAlbumId($album_id) as $tag)
            array_push($old_tag_names, $tag->name);
        foreach(array_diff($old_tag_names, $tag_names) as $to_delete)
            $this->removeTagFromAlbum($album_id, $to_delete);
        foreach(array_diff($tag_names, $old_tag_names) as $to_add)
            $this->addTagToAlbum($album_id, $to_add);
    }
    
    /*
     * get an instance of Album by id
     * return an instance of Album, or null
     */
    function getAlbumById($album_id)
    {
        $sql = "SELECT * FROM t_album WHERE f_id = :id";
		$stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":id", $album_id);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        if($row == false)
			return null;
		$album = new Album();
		$album->id = $row["f_id"];
		$album->name = $row["f_name"];
		$album->date = $row["f_date"];
		$album->tags = $this->getTagsByAlbumId($album->id);
		$album->user = $this->getUserById($row["f_user_id"]);
        return $album;
	}
	
    /*
     * get all the tags attached to the given photo,
     * <photo_id> is the id of the photo,
     * return an array containing all the Tag instance, or an empty array.
     */
    function getTagsByPhotoId($photo_id)
    {
        $sql = "SELECT * FROM t_tag, t_tagging WHERE t_tagging.f_photo_id = :photo_id and t_tag.f_id = t_tagging.f_tag_id";
		$stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":photo_id", $photo_id);
        $result = $stmt->execute();
        $all = array();
        while($row = $result->fetchArray(SQLITE3_ASSOC))
        {
            $tag = new Tag();
            $tag->id = $row["f_id"];
            $tag->name = $row["f_name"];
            array_push($all, $tag);
        }
        return $all;
    }

	/*
	 * get an array of instances of Photo
	 * return an array, maybe an empty array, each element is an instance of Photo
	 */
	function getPhotosByAlbum($album)
	{
        $sql = "SELECT * FROM t_photo WHERE f_album_id = :album_id";
		$stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":album_id", $album->id);
        $result = $stmt->execute();
        $all = array();
        while($row = $result->fetchArray(SQLITE3_ASSOC))
        {
			$photo = new Photo();
			$photo->id = $row["f_id"];
			$photo->file_name = $row["f_file_name"];
            $photo->album = $album;
            $photo->tags = $this->getTagsByPhotoId($photo->id);
            array_push($all, $photo);
        }
        return $all;
	}
	
	/*
	 * add a photo to DB,
	 * <photo> is an instance of Photo, with field <file_name> and <album> filled.
	 * return void, and <photo>->id will be filled.
	 */
	function addPhoto($photo)
	{
		$sql = "INSERT INTO t_photo (f_file_name, f_album_id) VALUES (:file_name, :album_id)";
		$stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":file_name", $photo->file_name);
		$stmt->bindValue(":album_id", $photo->album->id);
		$stmt->execute();
		$photo->id = $this->sqlite->lastInsertRowID();
	}
	
    /*
     * add a tag to a photo,
     * <photo_id> is the id of the photo, <tag_name> is a string,
     * return void
     */
    function addTagToPhoto($photo_id, $tag_name)
    {
        $tag = $this->getTagByName($tag_name);
        if($tag)
            $tag_id = $tag->id;
        else
        {
            $sql = "INSERT INTO t_tag (f_name) VALUES (:name)";
            $stmt = $this->sqlite->prepare($sql);
            $stmt->bindValue(":name", $tag_name);
            $stmt->execute();
            $tag_id = $this->sqlite->lastInsertRowID();
        }
        $sql = "SELECT * FROM t_tagging WHERE f_tag_id = :tag_id and f_photo_id = :photo_id";
        $stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":tag_id", $tag_id);
        $stmt->bindValue(":photo_id", $photo_id);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        if($row !== false)
            return;
        $sql = "INSERT INTO t_tagging (f_tag_id, f_photo_id) VALUES (:tag_id, :photo_id)";
        $stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":tag_id", $tag_id);
        $stmt->bindValue(":photo_id", $photo_id);
        $stmt->execute();
    }
    
    /*
     * remove a tag from the photo
     */
    function removeTagFromPhoto($photo_id, $tag_name)
    {
        $tag = $this->getTagByName($tag_name);
        if($tag == null)
            return;
        $sql = "DELETE FROM t_tagging WHERE f_tag_id = :tag_id and f_photo_id = :photo_id";
        $stmt = $this->sqlite->prepare($sql);
		$stmt->bindValue(":tag_id", $tag->id);
        $stmt->bindValue(":photo_id", $photo_id);
        $result = $stmt->execute();
    }
    
    /*
     * change the tags of a given photo,
     * <photo_id> is the id of the photo, <tag_names> is an array of string of the new tags,
     * return void
     */
    function changePhotoTags($photo_id, $tag_names)
    {
        $old_tag_names = array();
        foreach($this->getTagsByPhotoId($photo_id) as $tag)
            array_push($old_tag_names, $tag->name);
        foreach(array_diff($old_tag_names, $tag_names) as $to_delete)
            $this->removeTagFromPhoto($photo_id, $to_delete);
        foreach(array_diff($tag_names, $old_tag_names) as $to_add)
            $this->addTagToPhoto($photo_id, $to_add);
    }
    
    /*
     * remove a photo, <photo_id> is the id of the photo
     * if succeed, return true, otherwise return false
     */
    function removePhoto($photo_id)
    {
		$sql = "DELETE FROM t_photo WHERE f_id = :id";
        $stmt = $this->sqlite->prepare($sql);
        $stmt->bindValue(":id", $photo_id);
        $stmt->execute();
        $changes = $this->sqlite->changes();
		return $changes == 1;
	}
	
}

?>
