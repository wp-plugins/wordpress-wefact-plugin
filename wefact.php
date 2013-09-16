<?php
/*
	Plugin Name: Wordpress WeFact plugin
	Plugin URI: http://www.tussendoor.nl/wordpress-plugins/wefact-wordpress-plugin/
	Description: WeFact Plugin. For the installation guide <a href="http://www.tussendoor.nl/wordpress-plugins/wefact-wordpress-plugin/">click here.</a> | Voor de installatie handleiding <a href="http://www.tussendoor.nl/wordpress-plugins/wefact-wordpress-plugin/">klik hier.</a>
	Version: 2.0
	Author: Tussendoor internet & marketing
	Author URI: http://www.tussendoor.nl
*/

require_once(dirname(__FILE__).'/resources/functions.php');
require_once(dirname(__FILE__).'/resources/api.php');
require_once(dirname(__FILE__).'/resources/form.php');
require_once(dirname(__FILE__).'/resources/wefact.php');
require_once(dirname(__FILE__).'/resources/wefact-admin.php');

$WeFact = new WeFact();

add_action('admin_menu', array( &$WeFact, 'adminMenu' ));
register_activation_hook(__FILE__, array(&$WeFact, 'activate'));
wp_enqueue_style('wefact_style', plugins_url('/wordpress-wefact-plugin/css/style.css'));
load_plugin_textdomain('wp_wefact', false, basename(dirname(__FILE__)).'/languages' );
?>