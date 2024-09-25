<?php
/**
 * @Author: joeta
 * @Date:   2018-02-09 13:14:04
 * @Last Modified by:   joeta
 * @Last Modified time: 2018-03-13 12:57:45
 */
function eventer_edit_details_callback(){
	if(!isset($_GET['id'])){
		wp_die( $message = 'Cheatin\' Huh? I know where you live! See you soon!');
	}
	elseif (!is_numeric($_GET['id'])){
		wp_die( $message = 'Cheatin\' Huh? I know where you live! See you soooon!');
	}
	else{
		$attid = absint($_GET['id']);
		global $wpdb;
		$htmlout ='';
		$dashboard_url	= get_dashboard_url( '', 'admin.php?page=eventer-home');
		$eventer_table_name = $wpdb->prefix."eventer_users";
		$sqlforedit = "SELECT * FROM " . $eventer_table_name. " WHERE id=".$attid;
		require_once(ABSPATH. 'wp-admin/includes/upgrade.php');
		$renderque = $wpdb->get_results($sqlforedit);
		?><div class="wrap">
		<?php
			foreach ($renderque as $row){
				echo'
				<h1>Edit Details of '.$row->eventer_name.'</h1>
				<p>You are about to edit details of attendee submitted by them.</p>
				<a href="'.$dashboard_url.'" class="button">GO BACK</a>
				<br><br>
				<form action="" method="post" id="v_form">
					<div class="form-group">
						<input type="text" name="eventer_name" id="eventer_name" class="form-control textonly" placeholder="Your Name" value="'.$row->eventer_name.'"/>
					</div>';
					wp_nonce_field( 'eventer_nonce_action_two', 'eventernoncetwo' );
					echo'
					<div class="form-group">
						<input type="email" name="eventer_email" id="eventer_email" class="form-control textonly" placeholder="Your Email" value="'.$row->eventer_email.'"/>
					</div>
					<div class="form-group">
						<input type="tel" name="eventer_phone" id="eventer_phone" class="form-control textonly" placeholder="Your Phone" value="'.$row->eventer_phone.'"/>
					</div>
					<div class="form-group">
						<input type="url" name="eventer_url" id="eventer_url" class="form-control textonly" placeholder="Enter Your Website: http://" value="'.$row->eventer_url.'"/>
					</div>
					<div class="form-group">
						<label class="eventerlabels">A little bit about you?</label>
				    	<textarea name="eventer_about" id="eventer_about" class="form-control areaonly">'.$row->eventer_about.'</textarea>
					</div>
				    <input type="submit" class="btn btn-defaul" name="submit_form" value="Save Changes" />
				</form>
				';
			}?></div><?php
			if (current_user_can( 'manage_options' )) {
				if ( isset( $_POST["submit_form"] ) && check_admin_referer( 'eventer_nonce_action_two', 'eventernoncetwo' )) {
				    $username = sanitize_text_field($_POST["eventer_name"]);
				    $email = sanitize_email($_POST["eventer_email"]);
				    $phone = absint($_POST["eventer_phone"]);
				    $url = esc_url_raw( $_POST["eventer_url"] );
				    $about = sanitize_text_field($_POST["eventer_about"]);

				    $status = $wpdb->update(
			        $eventer_table_name,
			        array(
			            'eventer_name' => $username,
			            'eventer_email' => $email,
			            'eventer_phone' => $phone,
			            'eventer_url' => $url,
			            'eventer_about' => $about
			        ),
			        array('id' => $attid)
			    );
			    if ($status =  false) {
			    	echo '<br><p class="bg-danger">Changes Not Saved!</p>';
			    }
			    else{
			    	echo '<br><p class="bg-success">Changes Saved. Please Refresh.</p>';
			    }
			}
		}
	}
}
?>