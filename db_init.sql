CREATE TABLE `t_user` (
	`f_id`	INTEGER PRIMARY KEY AUTOINCREMENT,
	`f_name`	TEXT NOT NULL,
	`f_password`	TEXT NOT NULL
);

CREATE TABLE `t_activity` (
	`f_id`	INTEGER PRIMARY KEY AUTOINCREMENT,
	`f_name`	TEXT NOT NULL,
	`f_start_date`	INTEGER NOT NULL,
	`f_end_date`	INTEGER NOT NULL,
	`f_description`	TEXT,
	`f_user_id`	INTEGER NOT NULL,
	FOREIGN KEY(`f_user_id`) REFERENCES t_user ( f_id )
);

CREATE TABLE `t_join` (
	`f_user_id`	INTEGER NOT NULL,
	`f_activity_id`	INTEGER NOT NULL,
	FOREIGN KEY(`f_user_id`) REFERENCES t_user ( f_id ),
	FOREIGN KEY(`f_activity_id`) REFERENCES t_activity ( f_id )
);

CREATE TABLE `t_album` (
	`f_id`	INTEGER PRIMARY KEY AUTOINCREMENT,
	`f_name`	TEXT NOT NULL,
	`f_date`	INTEGER NOT NULL,
	`f_user_id`	INTEGER NOT NULL,
	FOREIGN KEY(`f_user_id`) REFERENCES t_user ( f_id )
);

CREATE TABLE `t_photo` (
	`f_id`	INTEGER PRIMARY KEY AUTOINCREMENT,
	`f_file_name`	TEXT NOT NULL,
	`f_album_id`	INTEGER NOT NULL,
	FOREIGN KEY(`f_album_id`) REFERENCES t_album ( f_id )
);

CREATE TABLE `t_tag` (
	`f_id`	INTEGER PRIMARY KEY AUTOINCREMENT,
	`f_name`	TEXT NOT NULL
);

CREATE TABLE `t_tagging` (
	`f_tag_id`	INTEGER,
	`f_photo_id`	INTEGER,
	`f_album_id`	INTEGER,
	FOREIGN KEY(`f_tag_id`) REFERENCES t_tag ( f_id ),
	FOREIGN KEY(`f_photo_id`) REFERENCES t_photo ( f_id ),
	FOREIGN KEY(`f_album_id`) REFERENCES t_album ( f_id )
);

CREATE TABLE `t_thumb` (
	`f_user_id`	INTEGER NOT NULL,
	`f_photo_id`	INTEGER NOT NULL,
	FOREIGN KEY(`f_user_id`) REFERENCES t_user ( f_id ),
	FOREIGN KEY(`f_photo_id`) REFERENCES t_photo ( f_id )
);

CREATE TABLE `t_comment` (
	`f_id`	INTEGER PRIMARY KEY AUTOINCREMENT,
	`f_user_id`	INTEGER NOT NULL,
	`f_photo_id`	INTEGER NOT NULL,
	`f_content`	TEXT NOT NULL,
	FOREIGN KEY(`f_user_id`) REFERENCES t_user ( f_id ),
	FOREIGN KEY(`f_photo_id`) REFERENCES t_photo ( f_id )
);

CREATE TABLE `t_group` (
	`f_id`	INTEGER PRIMARY KEY AUTOINCREMENT,
	`f_user_id`	INTEGER NOT NULL,
	`f_name`	TEXT NOT NULL,
	FOREIGN KEY(`f_user_id`) REFERENCES t_user ( f_id )
);

CREATE TABLE `t_group_member` (
	`f_group_id`	INTEGER NOT NULL,
	`f_user_id`	INTEGER NOT NULL,
	FOREIGN KEY(`f_group_id`) REFERENCES t_group ( f_id ),
	FOREIGN KEY(`f_user_id`) REFERENCES t_user ( f_id )
);

CREATE TABLE `t_subscribe` (
	`f_pub_user_id`	INTEGER NOT NULL,
	`f_sub_user_id`	INTEGER NOT NULL,
	FOREIGN KEY(`f_pub_user_id`) REFERENCES t_user ( f_id ),
	FOREIGN KEY(`f_sub_user_id`) REFERENCES t_user ( f_id )
);
