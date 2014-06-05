<?php
/*
Plugin Name: Wordpress WeFact plugin
Plugin URI: http://wordpress.org/plugins/wordpress-wefact-plugin/
Description: WeFact Plugin. For the installation guide <a href="http://www.tussendoor.nl/wordpress-plugins/wefact-wordpress-plugin/">click here.</a> | Voor de installatie handleiding <a href="http://www.tussendoor.nl/wordpress-plugins/wefact-wordpress-plugin/">klik hier.</a>
Version: 2.4
Author: Tussendoor internet & marketing
Author URI: http://www.tussendoor.nl
Tested up to: 3.9
*/

if ( ! defined('WPWF_PLUGIN_DIR')) define('WPWF_PLUGIN_DIR', dirname(__FILE__));
if ( ! defined('WPWF_PLUGIN_URL')) define('WPWF_PLUGIN_URL', plugins_url('wordpress-wefact-plugin'));

require_once(WPWF_PLUGIN_DIR.'/classes/class-wpwf.php');

if ( !class_exists('WPWF_API') ): require_once(WPWF_PLUGIN_DIR . '/classes/class-wpwf-api.php'); endif;

class WPWF_Plugin {

	private $api;
	private $error;
	private $limit = 10;
	private $old_api;

	public function __construct() {
		add_action('init', array($this, 'init'));
		add_action('admin_menu', array($this, 'admin_menu'));
		add_action('admin_enqueue_scripts', array($this, 'register_plugin_scripts'));
		register_activation_hook( __FILE__, array($this, 'activate'));
	}

	public function init() {
		load_plugin_textdomain('wp_wefact', false, dirname(plugin_basename(__FILE__)).'/languages' );
	}

	private function validate_setting() {
		
		$wpwf_api_key	= isset($_POST['wpwf_api_key']) ? $_POST['wpwf_api_key'] : get_option('wpwf_api_key');
		$wpwf_api_type 	= isset($_POST['wpwf_api_type']) ? $_POST['wpwf_api_type'] : get_option('wpwf_api_type');

		$wpwf_api_url 		= isset($_POST['wpwf_api_url']) ? $_POST['wpwf_api_url'] : get_option('wpwf_api_url');
		$wpwf_api_version 	= isset($_POST['wpwf_api_version']) ? $_POST['wpwf_api_version'] : get_option('wpwf_api_version');

		$wpwf_api_url 		= ($wpwf_api_type == 'standard' ? 'https://www.mijnwefact.nl/api/?wsdl&version=1.07' : $wpwf_api_url);

		if( empty($wpwf_api_url) || empty($wpwf_api_key) ):
			$this->error = 1; return false;
		elseif( ! empty($wpwf_api_url) || ! empty($wpwf_api_key) ):
			$this->api = new WPWF_API($wpwf_api_url, $wpwf_api_key, $wpwf_api_type, $wpwf_api_version);
		
			if( $wpwf_api_type == 'hosting' && $wpwf_api_version == '2.00' ):
				$this->old_api = false;
				if ( !$this->api->connection ):
					$this->error = 2; return false;
				endif;
			else:
				$this->old_api = true;
				if ( !$this->api->connection ):
					$this->error = 2; return false;
				endif;
			endif;
		endif;
		if( get_option('wpwf_active') == '0' ): $this->error = 2; return false; else: return true; endif;
	}

	public function activate() {
		$wpwf_api_url 	= ( get_option('clientURL') ? get_option('clientURL') : get_option('wefact_url') );
		$wpwf_api_key 	= ( get_option('clientSecuritykey') ? get_option('clientSecuritykey') : get_option('wefact_key') );
		$wpwf_api_type 	= get_option('wefact_type');

		if ( $wpwf_api_url ):
			update_option('wpwf_api_url', $wpwf_api_url);
			delete_option('clientURL');
			delete_option('wefact_url');
		endif;

		if ( $wpwf_api_key ):
			update_option('wpwf_api_key', $wpwf_api_key);
			delete_option('clientSecuritykey');
			delete_option('wefact_key');
		endif;

		if( $wpwf_api_type ):
			update_option('wpwf_api_type', $wpwf_api_type);
			delete_option('wefact_type');
		elseif( !get_option('wpwf_api_type') ):
			update_option('wpwf_api_type', 'standard');
		endif;
	}

	public function register_plugin_scripts() {
		wp_register_style( 'wefact_style', WPWF_PLUGIN_URL.'/assets/css/style.css' );
		wp_enqueue_style( 'wefact_style' );
		wp_enqueue_script( 'jquery' );
	}

	public function admin_menu() {
		add_menu_page('WeFact', 'WeFact', 'manage_options', 'wefact', array(&$this, 'wpwf_dashboard'), WPWF_PLUGIN_URL.'/assets/images/favicon.ico');
		
		if( $this->validate_setting() ):
			add_submenu_page ('wefact', __('Dashboard', 'wp_wefact'), __('Dashboard', 'wp_wefact'), 'manage_options', 'wpwf_dashboard', array(&$this, 'wpwf_dashboard'));
			add_submenu_page ('wefact', __('Debtors', 'wp_wefact'), __('Debtors', 'wp_wefact'), 'manage_options', 'wpwf_debtors', array(&$this, 'wpwf_debtors'));
			add_submenu_page ('wefact', __('Invoices', 'wp_wefact'), __('Invoices', 'wp_wefact'), 'manage_options', 'wpwf_invoices', array(&$this, 'wpwf_invoices'));
			add_submenu_page ('wefact', __('Pricequotes', 'wp_wefact'), __('Pricequotes', 'wp_wefact'), 'manage_options', 'wpwf_pricequotes', array(&$this, 'wpwf_pricequotes'));
			add_submenu_page ('wefact', __('Products', 'wp_wefact'), __('Products', 'wp_wefact'), 'manage_options', 'wpwf_products', array(&$this, 'wpwf_products'));

			//register single pages
			add_submenu_page (null, __('Debtor', 'wp_wefact'), __('Debtor', 'wp_wefact'), 'manage_options', 'wpwf_view_debtor', array(&$this, 'wpwf_view_debtor'));
			add_submenu_page (null, __('Invoice', 'wp_wefact'), __('Invoice', 'wp_wefact'), 'manage_options', 'wpwf_view_invoice', array(&$this, 'wpwf_view_invoice'));
			add_submenu_page (null, __('Pricequote', 'wp_wefact'), __('Pricequote', 'wp_wefact'), 'manage_options', 'wpwf_view_pricequote', array(&$this, 'wpwf_view_pricequote'));
		endif;
		
		add_submenu_page ('wefact', __('Settings', 'wp_wefact'), __('Settings', 'wp_wefact'), 'manage_options', 'wpwf_settings', array(&$this, 'wpwf_settings'));
		remove_submenu_page('wefact', 'wefact');
	}

	public function wpwf_dashboard() {
		if( $this->validate_setting() ):
			$listInvoices = $this->api->listInvoices(array(
				"Sort" 		 => "InvoiceCode",
				"Order" 	 => "ASC"
			));
			$invoices = array();
			if ($results = $listInvoices['result']) {
				foreach ($results as $row) {
					if ($row['Status'] == 2) {
						$invoices[] = $row;
					}
				}
			}

			$total = $this->getTotal( $listInvoices );

			include(WPWF_PLUGIN_DIR . '/views/dashboard.php');
		endif;
	}

	public function wpwf_debtors() {
		$data = array();
		$listDebtors = $this->api->listDebtors(array(
			"Sort" 		=> "CompanyName",
			"Order" 	=> "ASC"
		));	

		if ( ! $listDebtors['Error']) {
			if ( $results = $listDebtors['result'] ) {
				foreach ($results as $row) {
					$getDebtor = $this->api->getDebtor( $row['Identifier'] );
					$data[] = $getDebtor['result'];
				}
			}

			$curpage 		= (empty($_GET['p']) ? 1 : $_GET['p']);
			$pages 			= ceil($listDebtors['Count'] / $this->limit);
			$start 			= ($curpage - 1) * $this->limit;
			$debtors 		= array_slice($data, $start, $this->limit);
		}

		include(WPWF_PLUGIN_DIR . '/views/debtors.php');
	}

	public function wpwf_view_debtor() {
		$subscriptions = array();
		$getDebtor = $this->api->getDebtor( $_GET['id'] );

		$listSubscriptions = $this->api->listSubscriptions(array(
			'Debtor' => $id
		));

		if( $this->old_api ):
			$subscriptions = $listSubscriptions['result'];
		else:
			foreach ($listSubscriptions['result'] as $row) {
				$getSub = $this->api->getSubscription( $row['Identifier'] );
				$subscriptions[] = $getSub['result'];
			}
		endif;

		$listInvoices = $this->api->listInvoices(array(
			'Sort' => 'InvoiceCode',
			'Order' => 'ASC',
			'Debtor' => $_GET['id']
		));

		$listPriceQuotes = $this->api->listPriceQuotes(array(
			'Sort' => 'PriceQuoteCode',
			'Order' => 'ASC',
			'Debtor' => $_GET['id']
		));

		$debtor 		= $getDebtor['result'];
		$invoices 		= $listInvoices['result'];
		$pricequotes 	= $listPriceQuotes['result'];

		include(WPWF_PLUGIN_DIR . '/views/view.debtor.php');
	}

	public function wpwf_invoices() {
		$listInvoices = $this->api->listInvoices(array(
			"Sort" 		 => "InvoiceCode",
			"Order" 	 => "ASC"
		));

		if ( !$listInvoices['error'] ) {
			$curpage 		= (empty($_GET['p']) ? 1 : $_GET['p']);
			$pages 			= ceil($listInvoices['count'] / $this->limit);
			$start 			= ($curpage - 1) * $this->limit;
			$invoices 		= array_slice($listInvoices['result'], $start, $this->limit);
		}

		include(WPWF_PLUGIN_DIR . '/views/invoices.php');
	}

	public function wpwf_view_invoice() {
		if( isset($_GET['status']) && isset($_GET['id']) && $_GET['status'] == 4 ):
			$this->api->changeInvoiceStatus($_GET['id'], 'true');
			wp_redirect( admin_url('admin.php?page=wpwf_view_invoice&id='.$_GET['id'].'&msg=3') );
		else:
			$getInvoice = $this->api->getInvoice($_GET['id']);
			$invoice 	= $getInvoice['result'];
			include(WPWF_PLUGIN_DIR . '/views/view.invoice.php');
		endif;
	}

	public function wpwf_pricequotes() {
		$listPriceQuotes = $this->api->listPriceQuotes();

		if ( !$listPriceQuotes['error'] ) {
			$curpage 		= (empty($_GET['p']) ? 1 : $_GET['p']);
			$pages 			= ceil($listPriceQuotes['count'] / $this->limit);
			$start 			= ($curpage - 1) * $this->limit;
			$pricequotes 	= array_slice($listPriceQuotes['result'], $start, $this->limit);
		}

		include(WPWF_PLUGIN_DIR . '/views/pricequotes.php');
	}

	public function wpwf_view_pricequote() {
		if( isset($_GET['status']) && isset($_GET['id']) ):
			if( $_GET['status'] == 3 ): $update = 'true'; $msg = 4;
			elseif( $_GET['status'] == 8 ): $update = 'false'; $msg = 5; endif;

			$this->api->changePriceQuoteStatus($_GET['id'], $update);
			wp_redirect( admin_url('admin.php?page=wpwf_view_pricequote&id='.$_GET['id'].'&msg='.$msg) );
		else:
			$getPriceQuote = $this->api->getPriceQuote($_GET['id']);
			$pricequote = $getPriceQuote['result'];

			$getDebtor = $this->api->getDebtor($pricequote['Debtor']);
			$debtor = $getDebtor['result'];

			include(WPWF_PLUGIN_DIR . '/views/view.pricequote.php');
		endif;
	}

	public function wpwf_products() {
		$listProducts = $this->api->listProducts();

		if ( ! $listProducts['error'] ) {
			$curpage 		= (empty($_GET['p']) ? 1 : $_GET['p']);
			$pages 			= ceil($listProducts->Count / $this->limit);
			$start 			= ($curpage - 1) * $this->limit;
			$products 		= array_slice($listProducts['result'], $start, $this->limit);
		}

		include(WPWF_PLUGIN_DIR . '/views/products.php');
	}

	public function wpwf_settings() {
		if ( ! empty( $_POST ) ) {
			if ($_POST['wpwf_api_type'] == 'hosting') {
				update_option('wpwf_api_url', $_POST['wpwf_api_url']);
				update_option('wpwf_api_version', $_POST['wpwf_api_version']);
			}
			update_option('wpwf_api_type', $_POST['wpwf_api_type']);
			update_option('wpwf_api_key', $_POST['wpwf_api_key']);
		}
		$display = (get_option('wpwf_api_type') == 'standard' ? 'none' : 'block');

		include(WPWF_PLUGIN_DIR . '/views/settings.php');
	}

	private function getTotal( $invoice = null ) {
		$listInvoices = ( $invoice ? $invoice : $this->api->listInvoices() );


		$total = array('revenue' => 0, 'invoices' => 0);

		if ($results = $listInvoices['result']) {
			foreach ($results as $row) {
				$date = date('Y', strtotime($row['Date']));
				if ( $row['Status'] == 4 && $date == date('Y') ) {
					$total['revenue'] += $row['AmountExcl'];
					$total['invoices'] += 1;
				}
			}
		}

		$listSubscriptions = $this->api->listSubscriptions();

		if ($results = $listSubscriptions['result']) {
			foreach ($results as $row) {
				$date = date('Y', strtotime($row['NextDate']));
				if ($date == date('Y')) {
					$total['revenue'] += $row['PriceExcl'];
				}
			}
		}

		return $total;
	}
	
	private function showMsg() {
		if( $this->error || isset($_GET['msg']) ) {
			$tmp = WPWF::messages( $this->error );
			echo '<div class="'.$tmp['class'].'">'.$tmp['message'].'</div>';
		}
	}

}

new WPWF_Plugin();
?>