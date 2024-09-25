<?php
function eventer_activation_func(){
	//Create the table for user sign ups
	global $wpdb;
	$eventer_table_name = $wpdb->prefix."eventer_users";
	$charset_collate = $wpdb->get_charset_collate();
	if ($wpdb->get_var('SHOW TABLES LIKE '.$eventer_table_name) != $eventer_table_name){
		$sql_query = "CREATE TABLE ".$eventer_table_name."(
			id INT NOT NULL AUTO_INCREMENT,
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			eventer_name CHAR(50),
			eventer_email CHAR(50),
			eventer_phone CHAR(20),
			eventer_url CHAR(50),
			eventer_image VARCHAR(1000),
			eventer_about VARCHAR(1000),
			user_agent VARCHAR(255),
			PRIMARY KEY(id)
		) $charset_collate;";
		require_once(ABSPATH. 'wp-admin/includes/upgrade.php');
		dbDelta($sql_query);
		// What if we need to updrage database later? For comparison for later time.
		add_option('eventer_database_version','0.0.1');
		//Create the folder for image uploads
		$upload = wp_upload_dir();
   	 	$upload_dir = $upload['basedir'];
    	$upload_dir = $upload_dir . '/eventer_files';
    	if (! is_dir($upload_dir)) {
       		mkdir( $upload_dir, 0700 );
    	}
	}
}
?>