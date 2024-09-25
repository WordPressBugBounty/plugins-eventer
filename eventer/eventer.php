<?php
/**
    Plugin Name: Eventer
    Plugin URI: http://wordpress.com/eventer
    Description: This plugin is meant for event registration and display the registered users on a page.
    Version: 0.1.1
    Author: Jyothis Joy
    Author URI: http://jyothisjoy.com
    Text Domain: eventer
    Domain Path: /languages

    License: GPL2
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}
// Variables We Need, just In case
define( 'eventer_VERSION', '0.0.1' );
define( 'eventer__MINIMUM_WP_VERSION', '4.0' );
define( 'eventer__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
// File Inclusions.
include 'includes/navigation.php';
include 'includes/activation.php';
include 'eventer-home.php';
include 'includes/frontend.php';
include 'includes/editdetails.php';
//Hooks & Actions
register_activation_hook(__FILE__,'eventer_activation_func');
add_action('admin_menu', 'eventer_navigation_register');
add_action( 'admin_enqueue_scripts', 'load_eventer_wp_admin_style' );
add_action( 'wp_enqueue_scripts', 'eventer_load_frontend_styles' );
add_action( 'plugins_loaded', 'eventer_translateinitfunction');
add_shortcode( 'eventer_submit_form', 'eventer_frontend_submition' );
add_shortcode( 'eventer_user_display', 'eventer_frontend_user_display');
//Eneque
function eventer_load_frontend_styles() {
    wp_register_style('eventer_css', plugins_url('/css/style.css', __FILE__, false, '0.0.1'));
    wp_enqueue_style('eventer_css');
}
function load_eventer_wp_admin_style($hook){
    if($hook == 'toplevel_page_eventer-home' || $hook == 'eventer_page_eventer-settings' || $hook == 'admin_page_eventer-edit-details') {
        wp_register_style( 'bootstrap', plugins_url('/css/bootstrap.css', __FILE__, false, '3.7.1'));
        wp_enqueue_style( 'bootstrap');
    }
}
function eventer_translateinitfunction(){
    load_plugin_textdomain( 'eventer', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
?>