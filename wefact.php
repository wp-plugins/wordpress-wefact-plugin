<?php
/*
	Plugin Name: Wordpress WeFact plugin
	Plugin URI: http://www.tussendoor.nl/wordpress-plugins/wefact-wordpress-plugin/
	Description: WeFact Plugin. For the installation guide <a href="http://www.tussendoor.nl/wordpress-plugins/wefact-wordpress-plugin/">click here.</a> | Voor de installatie handleiding <a href="http://www.tussendoor.nl/wordpress-plugins/wefact-wordpress-plugin/">klik hier.</a>
	Version: 1.1
	Author: Tussendoor internet & marketing
	Author URI: http://www.tussendoor.nl
	
*/

require_once(dirname(__FILE__) . '/wefact_functions.php');
if(is_admin())
{
	require_once(dirname(__FILE__) . '/wefact_admin.php');
}

class WeFact
{
	public function __construct()
	{
		register_activation_hook(__FILE__, array(&$this, 'activate'));
	}
	
	public function activate()
	{
		if(!class_exists('SoapClient'))
		{
			die(__('Plugin requires that the Soap Client is installed on the server.', 'wefact'));
		}
	}
}

new WeFact();
