<?php

require_once(dirname(__FILE__) . '/api.php');
require_once(dirname(__FILE__) . '/wefact_invoices_list_table.php');
require_once(dirname(__FILE__) . '/wefact_debtors_list_table.php');
require_once(dirname(__FILE__) . '/wefact_dashboard_link_list_table.php');

class WeFact_Admin
{
	
	private $_pages = array();
	
	public  $clientURL;
	public  $clientSecuritykey;
	
	public function __construct()
	{
		add_action('init', array(&$this, 'do_output_buffer'));
		
		add_action('init', array(&$this, 'init')); 
		add_action('admin_menu', array(&$this, 'admin_menu'));
		add_action('admin_menu', array(&$this, 'load_resources'), 100);
		add_action('wp_dashboard_setup', array(&$this, 'dashboard_widget'));
		add_action('load-toplevel_page_wefact', array(&$this, 'page_load_pdf'), 1);
	}
	
	public function init()
	{
		load_plugin_textdomain('wefact', FALSE, dirname(plugin_basename(__FILE__)) . '/languages');
	}
	
	function do_output_buffer() 
	{
		ob_start();
	}
	
	public function load_resources()
	{
		foreach($this->_pages as $page){
			add_action('admin_print_scripts-' . $page, array(&$this, 'load_scripts'));
			add_action('admin_print_styles-' . $page, array(&$this, 'load_styles'));
		}
	}
	
	public function load_scripts()
	{
		
	}
	
	public function load_styles()
	{
	//	wp_enqueue_style('wefact', plugins_url('recources/wefact.css', __FILE__));
	}
	
	public function admin_menu()
	{
		$this->_pages[] = add_menu_page(__('WeFact', 'wefact'), __('Wefact', 'wefact'), 'manage_options', 'wefact', array(&$this, 'invoices'));
		$this->_pages[] = add_submenu_page('wefact', __('Invoices', 'wefact'), __('Invoices', 'wefact'), 'manage_options', 'wefact', array(&$this, 'invoices'));
		$this->_pages[] = add_submenu_page('wefact', __('Debtors', 'wefact'), __('Debtors', 'wefact'), 'manage_options', 'wefact/debtors', array(&$this, 'debtors'));
		$this->_pages[] = add_submenu_page('wefact', __('Settings', 'wefact'), __('Settings', 'wefact'), 'manage_options', 'wefact/settings', array(&$this, 'settings'));
		$this->_pages[] = add_submenu_page('wefact', __('About', 'wefact'), __('About', 'wefact'), 'manage_options', 'wefact/about', array(&$this, 'about'));	
	}
	
	// --------------------------------------------------------------------- //
	//    INVOICES	                                                             //
	// --------------------------------------------------------------------- //
	
	public function page_load_pdf()
	{	
		if(isset($_GET['action']) && strtolower($_GET['action']) === 'pdf')
		{
			$this->view_pdf($_GET['invoice']);
		}
	}
	
	public function invoices()
	{	
		if(isset($_GET['action']) && $_GET['action'] === 'view')
		{
			$this->view_invoice($_GET['invoice']);
		}
//		elseif(isset($_GET['action']) && strtolower($_GET['action']) === 'email')
//		{
//			$this->sent_mail($_GET['invoice']);
//		}
		elseif(isset($_GET['action']) && strtolower($_GET['action']) === 'status')
		{
			$this->change_status($_GET['invoice']);
		}
		else
		{
			wefact_render_invoice_list_page();
		}
	}
	
	public function view_invoice($id)
	{
		$api			= new WeFactAPI();
		
		$obj			= $api->getInvoiceID($id)->Result->Value;
		$invoice_id		= object_to_array($obj);
		$invoice_info	= $api->getInvoice($invoice_id);
	
		$this->_load_view('view_invoice', compact('invoice_info', 'id'));
	}
	
	public function view_pdf($id)
	{
		$api			= new WeFactAPI();
		
		$obj			= $api->getInvoiceID($id)->Result->Value;
		$invoice_id 	= object_to_array($obj);
		$invoice	 	= $api->downloadInvoice($invoice_id);
		$filename_pdf	= $id.'.pdf';
		
		if($invoice->Status == 'success' && isset($invoice->Result->PDF))
		{
			header("Cache-Control: public, must-revalidate");
			header("Pragma: hack");
			header('Content-Type: application/pdf');
			header('Content-Disposition: attachment; filename="'.$filename_pdf.'"');
			header("Content-Transfer-Encoding: binary");
			
			echo base64_decode($invoice->Result->PDF);
			die();
		}
	}

//	public function sent_mail($id)
//	{
//		$api		= new WeFactAPI();
//		
//		$obj		= $api->getInvoiceID($id)->Result->Value;
//		$invoice_id = object_to_array($obj);
//		$invoice	= $api->sendInvoiceByEmail($invoice_id);
//		
//		print_r($invoice->Result->Value);
//		wefact_render_invoice_list_page();
//	}
	
	public function change_status($id)
	{
		$api		= new WeFactAPI();
		
		$obj		= $api->getInvoiceID($id)->Result->Value;
		$invoice_id = object_to_array($obj);
		$result 	= $api->changeInvoiceStatus($invoice_id, 'true'); // 'false' = not paid, 'true' = paid

		if($result->Status == 'error') {
			print_r($result->Error);
		}
		else
		{
			$status = "De status van de factuur is gewijzigd naar betaald.";
			print_r($status);
		}
		$this->view_invoice($id);
	}
	
	// --------------------------------------------------------------------- //
	//    DEBTORS	                                                             //
	// --------------------------------------------------------------------- //

	public function debtors()
	{
		if(isset($_GET['action']) && $_GET['action'] === 'view')
		{
			$this->view_debtor($_GET['debtor']);
		}
		else
		{
			wefact_render_debtors_list_page();
		}	
	}
	
	public function view_debtor($id)
	{
		$api		= new WeFactAPI();
		
		$obj		= $api->getDebtorID($id)->Result->Value;
		$debtor_id	= object_to_array($obj);
		$debtor_info= $api->getDebtor($debtor_id);
			
		$this->_load_view('view_debtor', compact('debtor_info'));
	}
	
	// --------------------------------------------------------------------- //
	//    SETTINGS	                                                             //
	// --------------------------------------------------------------------- //

	public function settings()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			update_option('wefact_dashboard_toggle', isset($_POST['wefact_dashboard_toggle']) ? '1' : '0');
		}
		if(!empty($_POST))
		{
			$this->clientURL = $_POST['clientURL'];
			$this->clientSecuritykey = $_POST['clientSecuritykey'];
			
			if(isset($this->clientURL))
			{
				update_option('clientURL', $this->clientURL);
			}
			if(isset($this->clientSecuritykey))
			{
				update_option('clientSecuritykey', $this->clientSecuritykey);
			}
		}
		if($this->clientURL === '' || $this->clientSecuritykey === '')
			{
				update_option('wefact_dashboard_toggle', isset($_POST['wefact_dashboard_toggle']) ? '0' : '');
				$return = doer_of_stuff();
					if ( is_wp_error($return) )
					echo $return->get_error_message();
			}
		$this->_load_view('view_options');
	}
	
	public function about()
	{
		$this->_load_view('view_about');
	}
	
	// --------------------------------------------------------------------- //
	//    PRIVATE                                                            //
	// --------------------------------------------------------------------- //
	
	private function _load_view($__template, $vars = array())
	{
		extract($vars, EXTR_SKIP);
		include(dirname(__FILE__) . '/views/' . $__template . '.php');
	}
	
	// --------------------------------------------------------------------- //
	//    DASHBOARD WIDGET                                                   //
	// --------------------------------------------------------------------- //

	public function wefact_dashboard_widget_function() 
	{	
		wefact_render_dashboard_invoice_list_page();
	} 

	public function dashboard_widget()
	{
		if(get_option('wefact_dashboard_toggle'))
		{
			wp_add_dashboard_widget('wefact_dashboard_widget', __('WeFact', 'wefact'), array(&$this, 'wefact_dashboard_widget_function'));
			
			global $wp_meta_boxes;
			
			$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
			
			$wefact_widget_backup = array('wefact_dashboard_widget' => $normal_dashboard['wefact_dashboard_widget']);
			unset($normal_dashboard['wefact_dashboard_widget']);
			
			$sorted_dashboard = array_merge($wefact_widget_backup, $normal_dashboard);
			
			$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
		}
	}
}
new WeFact_Admin();
