<?php
//Create the main menu navigation in the wordpress dashboard
function eventer_navigation_register(){
	add_menu_page( 'Eventer', 'Eventer', 'manage_options', 'eventer-home', 'evnetr_display_home', '', 6 );
	add_submenu_page( 'eventer-home', 'eventer Settings', 'Settings', 'manage_options', 'eventer-settings', 'eventer_settings_display');
	add_submenu_page(null, 'Edit Eventer Attendee','Edit Eventer Attendee','manage_options', 'eventer-edit-details', 'eventer_edit_details_callback'
     );
}
?>