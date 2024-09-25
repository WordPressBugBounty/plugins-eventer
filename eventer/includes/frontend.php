<?php
function eventer_frontend_submition(){
	$inisizevariable = ini_get("upload_max_filesize");
	$inisizevariableinb = $inisizevariable*pow(1024,2);
	?><form action="" method="post" id="v_form" enctype="multipart/form-data">
	    <input type="text" name="eventer_name" id="eventer_name" class="eventer_form_control textonly" placeholder="Your Name" />
	    <?php wp_nonce_field( 'eventer_nonce_action', 'eventernonce' ); ?>
	    <input type="email" name="eventer_email" id="eventer_email" class="eventer_form_control textonly" placeholder="Your Email" />
	    <input type="tel" name="eventer_phone" id="eventer_phone" class="eventer_form_control textonly" placeholder="Your Phone" />
	    <input type="url" name="eventer_url" id="eventer_url" class="eventer_form_control textonly" placeholder="Enter Your Website: http://" />
	    <label>Choose your display picture:<br>Acceptable formats: jpg,png,gif,jpeg<br>Maximum file size:<?php echo $inisizevariable ?>B</label>
	    <input type="file" name="file" id="file" class="eventer_form_control" accept="image/*">
	    <br>
	    <label class="eventerlabels">A little bit about you?</label>
	    <textarea name="eventer_about" id="eventer_about" class="eventer_form_control areaonly"></textarea>
	    <br>
	    <input type="submit" class="button eventer_button" name="submit_form" value="Submit" />
	</form>
	<?php
	if (! isset( $_POST['eventernonce'] ) || ! wp_verify_nonce( $_POST['eventernonce'], 'eventer_nonce_action' )){
		print 'Sorry, your nonce did not verify.';
   		exit;
	}
	if ( isset( $_POST["submit_form"] )) {
		if (isset($_FILES['file']['name'])){
			$name = sanitize_file_name($_FILES['file']['name']);
			$target = wp_upload_dir();
	   		$target_dir = $target['basedir'];
	    	$target_dir = $target_dir . '\eventer_files';
	 		$target_file = $target_dir . basename(sanitize_file_name($_FILES["file"]["name"]));
	 		$maxsize = $inisizevariableinb;
	 		$errors     = array();
	 		// Select file type
	 		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	 		// Valid file extensions
	 		$extensions_arr = array("jpg","jpeg","png","gif");
	 		if(($_FILES['file']['size'] >= $maxsize) || ($_FILES["file"]["size"] == 0)) {
	 		        $errors[] = 'File too large. File must be less than'.$inisizevariable.'B.';
	 		}
			global $wpdb;
		    $username = sanitize_text_field($_POST["eventer_name"]);
		    $email = sanitize_email($_POST["eventer_email"]);
		    $phone = absint($_POST["eventer_phone"]);
		    $url = esc_url_raw( $_POST["eventer_url"] );
		    $about = sanitize_text_field($_POST["eventer_about"]);
			if( in_array($imageFileType,$extensions_arr) ){
			    $wpdb->insert(
			        $eventer_table_name,
			        array(
			            'eventer_name' => $username,
			            'eventer_email' => $email,
			            'eventer_phone' => $phone,
			            'eventer_url' => $url,
			            'eventer_about' => $about,
			            'eventer_image' => $name
			        )
			    );
			    // Convert to base64
			    $image_base64 = base64_encode(file_get_contents($_FILES['file']['tmp_name']) );
			    $image = 'data:image/'.$imageFileType.';base64,'.$image_base64;
			   // Upload file
		  		if(count($errors) === 0) {
					move_uploaded_file($_FILES['file']['tmp_name'],"$target_dir/$name");
		  		}
		  		else{
		  			foreach ($errors as $error) {
		  				echo '<script>alert("'.$error.'")</script>';
		  			}
		  		}
		  		$html = "<p>successfully recorded. Thanks!!</p>";
		    	return $html;
			}
			else{
				$html = '<p>Not submitted. Some sort of issue.</p>';
				return $html;
			}
		}
		else{
			echo '<script>alert("No file attached")</script>';
		}
	}
}
function eventer_frontend_user_display(){
	global $wpdb;
	$eventer_table_name = $wpdb->prefix."eventer_users";
	$sqlforpost = "SELECT * FROM " . $eventer_table_name. " ORDER BY id ";
	$imgpath_alfa = wp_upload_dir();
	$imagepath_beta = $imgpath_alfa['baseurl'].'/eventer_files/';
	require_once(ABSPATH. 'wp-admin/includes/upgrade.php');
	$renderque = $wpdb->get_results($sqlforpost);
	echo '<div class="container">';
	foreach ($renderque as $row) {
		$imagepath = esc_url( $imagepath_beta.$row->eventer_image );
		$eventer_url_out = esc_url( $row->eventer_url, null, 'display' );
		$eventer_about_out = esc_html( $row->eventer_about );
		$eventer_name_out = esc_html( $row->eventer_name );
		echo '
			<div class="section group">
				<div class="col span_1_of_3">
					<img src="'.$imagepath.'" class="eventerimage">
				</div>
				<div class="col span_4_of_3">
					<div class="eventername section group">
						<a href="'.$eventer_url_out.'"><b>'.$eventer_name_out .'</b></a>
					</div>
					<div class="section group">
						'.$eventer_about_out.'
					</div>
				</div>
			</div>
		';
	}
	echo '</div>';
}
?>