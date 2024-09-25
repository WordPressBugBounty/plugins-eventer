<?php
// HTML for the main dashboard.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
function evnetr_display_home(){
	global $wpdb;
	$eventer_table_name = $wpdb->prefix."eventer_users";
	$sqlforpost = "SELECT * FROM " . $eventer_table_name. " ORDER BY id ";
	require_once(ABSPATH. 'wp-admin/includes/upgrade.php');
	$renderque = $wpdb->get_results($sqlforpost);
	?><div class="wrap">
		<h1><?php _e('Attentees','eventer'); ?></h1><br>
		<table class="table">
			<tr>
				<td><b><?php _e('ID','eventer'); ?></b></td>
				<td><b><?php _e('Name','eventer'); ?></b></td>
				<td><b><?php _e('Email','eventer'); ?></b></td>
				<td><b><?php _e('Phone','eventer'); ?></b></td>
				<td><b><?php _e('Action','eventer'); ?>Action</b></td>
				<td><b><?php _e('Action','eventer'); ?></b></td>
			</tr>
		<?php
		$dashboard_url	= get_dashboard_url( '', 'admin.php?page=eventer-edit-details');
		foreach ($renderque as $row){
			$eventer_url_out = esc_url( $row->eventer_url, null, 'display' );
			$eventer_about_out = esc_html( $row->eventer_about );
			$eventer_name_out = esc_html( $row->eventer_name );
			$eventer_email_out = esc_html($row->eventer_email);
			$eventer_phone_out = esc_html( $row->eventer_phone );
			echo '
			<tr>
				<td>' . $row->id . '</td>
				<td><a href="'.$eventer_url_out.'">' . $eventer_name_out. '</a></td>
				<td><a href="mailto:'.$eventer_email_out .'">' . $eventer_email_out . '</a></td>
				<td>' . $eventer_phone_out . '</td>
				<td><form method="post" action="'?><?php echo $_SERVER['PHP_SELF'].'?page=eventer-home'; echo'"><input type="hidden" name="deletevalue" value="'.$row->id.'"><input class="button" type="submit" name="deleteeventeruser" value="Delete"></form>
				</td>
				<td><a class="button" href="'.$dashboard_url.'&id='.$row->id.'">Edit</a></td>
			</tr>';
		}
		?></table>
	</div>
	<?php
	if (isset($_POST['deleteeventeruser'])) {
		$rowid = stripcslashes($_POST['deletevalue']);
		if (is_numeric($rowid)) {
			$wpdb;
			$eventer_table_name = $wpdb->prefix."eventer_users";
			$wpdb->delete($eventer_table_name, array( 'id' => $rowid) );
		}
	}
}
function eventer_settings_display(){
?><div class="wrap">
	<div class="row">
		<div class="col-md-8">
			<h2><?php _e('Eventer Settings','evneter'); ?></h2>
			<div class="row">
				<div class="col-md-12">
					<p><?php _e('Paste this shortcode for registration.','evneter'); ?></p>
					<input type="text" name="text" disabled value="[eventer_submit_form]">
					<br><br>
				</div>
				<div class="col-md-12">
					<p><?php _e('Paste this shortcode for attendee list.','evneter'); ?></p>
					<input type="text" name="text" disabled value="[eventer_user_display]">
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<h2><?php _e('Status','evneter'); ?></h2>
			<p>
				<?php _e('Maximum Upload Size: ','evneter'); ?><?php echo ini_get("upload_max_filesize"); ?>B
				<br>
				<?php _e('To increase image upload size go to php.ini and change ','evneter'); ?><code>upload_max_filesize: <?php echo ini_get("upload_max_filesize"); ?></code>
			</p>
		</div>
	</div>
	</div>
<?php
}
?>