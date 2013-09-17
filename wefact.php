<?php
/*
	Plugin Name: Wordpress WeFact plugin
	Plugin URI: http://www.tussendoor.nl/wordpress-plugins/wefact-wordpress-plugin/
	Description: WeFact Plugin. For the installation guide <a href="http://www.tussendoor.nl/wordpress-plugins/wefact-wordpress-plugin/">click here.</a> | Voor de installatie handleiding <a href="http://www.tussendoor.nl/wordpress-plugins/wefact-wordpress-plugin/">klik hier.</a>
	Version: 2.1
	Author: Tussendoor internet & marketing
	Author URI: http://www.tussendoor.nl
*/

require_once(dirname(__FILE__).'/functions.php');
require_once(dirname(__FILE__).'/api.php');

class WeFact {

	private $api;
	private $limit = 10;
	private $tabs = array();
	private $urlparts = array();
	private $rendered = false;
	private $routes  = array(
		'dashboard'                => 'viewDashboard',
		'debtors'                  => 'listDebtors',
		'debtors/view/*'           => 'viewDebtor',
		'invoices'                 => 'listInvoices',
		'invoices/view/*'          => 'viewInvoice',
		'invoices/paid/*'          => 'setInvoicePaid',
		'pricequotes'              => 'listPricequotes',
		'pricequotes/view/*'       => 'viewPricequote',
		'pricequotes/accepted/*'   => 'setPricequoteAccepted',
		'pricequotes/declined/*'   => 'setPricequoteDeclined',
		'products'                 => 'listProducts',
		'settings'                 => 'settings'
	);

	public function __construct()
	{
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
		register_activation_hook( __FILE__, array($this, 'activate') );

		$this->tabs = array(
			'dashboard'     => __('Dashboard', 'wp_wefact'),
			'debtors'       => __('Debtors', 'wp_wefact'),
			'invoices'      => __('Invoices', 'wp_wefact'),
			'pricequotes'   => __('Pricequotes', 'wp_wefact'),
			'products'      => __('Products', 'wp_wefact'),
			'settings'      => __('Settings', 'wp_wefact')
		);

		$this->urlparts = explode('/', (empty($_GET['route']) ? 'dashboard' : $_GET['route']));

		$wefact_url	= get_option('wefact_url');
		$wefact_key	= get_option('wefact_key');

		if ( ! class_exists('SoapClient') ) {
			die(__('Plugin requires that the Soap Client is installed on the server.', 'wefact'));
		}

		if ($_GET['route'] != 'settings') {
			if ( (empty($wefact_url) || empty($wefact_key)) ) {
				$this->redirect('&route=settings&msg=1');
			}
			elseif ( ! empty($wefact_url) || ! empty($wefact_key)) {
				$this->api = new WeFactAPI($wefact_url, $wefact_key);
				if ( ! $this->api->client) {
					$this->redirect('&route=settings&msg=2');
				}
			}
		}
	}

	public function init()
	{
		load_plugin_textdomain('wp_wefact', false, dirname(plugin_basename(__FILE__)).'/languages' );
	}

	public function activate()
	{
		if ($wefact_url = get_option('clientURL')) {
			update_option('wefact_url', $wefact_url);
			delete_option('clientURL');
		}

		if ($wefact_key = get_option('clientSecuritykey')) {
			update_option('wefact_key', $wefact_key);
			delete_option('clientSecuritykey');
		}
	}

	public function register_plugin_styles()
	{
		wp_register_style( 'wefact_style', plugins_url( 'wordpress-wefact-plugin/css/style.css' ) );
		wp_enqueue_style( 'wefact_style' );
	}

	public function admin_menu()
	{
		add_menu_page('WeFact', 'WeFact', 'manage_options', 'wefact', array(&$this, 'route'), plugins_url('wordpress-wefact-plugin/images/favicon.ico'));
	}

	public function viewDashboard()
	{
		$listInvoices = $this->api->listInvoices(array(
			"Sort" 		 => "InvoiceCode",
			"Order" 	 => "ASC"
		));
		$invoices = array();
		if ($results = $listInvoices->Result->Invoices) {
			foreach ($results as $row) {
				if ($row->Status == 2) {
					$invoices[] = $row;
				}
			}
		}

		$viewData = array(
			'invoices' => $invoices,
			'total' => $this->getTotal()
		);

		$this->render('dashboard', $viewData);
	}

	public function listDebtors()
	{
		$data = array();
		$listDebtors = $this->api->listDebtors(array(
			"Sort" 		 => "CompanyName",
			"Order" 	 => "ASC"
		));	

		if ( ! $listDebtors->Error) {
			if ($results = $listDebtors->Result->Debtors) {
				foreach ($results as $row) {
					$getDebtor = $this->api->getDebtor($row->Identifier);
					$data[] = $getDebtor->Result->Debtor;
				}
			}

			$curpage 		= (empty($_GET['p']) ? 1 : $_GET['p']);
			$pages 			= ceil($listDebtors->Count / $this->limit);
			$start 			= ($curpage - 1) * $this->limit;
			$debtors 		= array_slice($data, $start, $this->limit);

			$viewData = array(
				'debtors' => $debtors,
				'pages' => $pages,
				'curpage' => $curpage
			);
		}

		$this->render('list-debtors', $viewData);
	}

	public function viewDebtor($id)
	{
		$getDebtor = $this->api->getDebtor($id);

		$listSubscriptions = $this->api->listSubscriptions(array(
			'Debtor' => $id
		));

		$listInvoices = $this->api->listInvoices(array(
			'Debtor' => $id
		));

		$listPriceQuotes = $this->api->listPriceQuotes(array(
			'Debtor' => $id
		));

		$viewData = array(
			'debtor' => $getDebtor->Result->Debtor,
			'subscriptions' => $listSubscriptions->Result->Subscriptions,
			'invoices' => $listInvoices->Result->Invoices,
			'pricequotes' => $listPriceQuotes->Result->PriceQuotes
		);

		$this->render('view-debtor', $viewData);
	}

	public function listInvoices()
	{
		$listInvoices = $this->api->listInvoices(array(
			"Sort" 		 => "InvoiceCode",
			"Order" 	 => "ASC"
		));

		if ( ! $listInvoices->Error) {
			$curpage 		= (empty($_GET['p']) ? 1 : $_GET['p']);
			$pages 			= ceil($listInvoices->Count / $this->limit);
			$start 			= ($curpage - 1) * $this->limit;
			$invoices 		= array_slice($listInvoices->Result->Invoices, $start, $this->limit);

			$viewData = array(
				'invoices' => $invoices,
				'pages' => $pages,
				'curpage' => $curpage
			);			
		}
		$this->render('list-invoices', $viewData);
	}

	public function viewInvoice($id)
	{
		$getInvoice = $this->api->getInvoice($id);
		$this->render('view-invoice', array('invoice' => $getInvoice->Result->Invoice));
	}

	public function setInvoicePaid($id)
	{
		$this->api->changeInvoiceStatus($id, 'true');
		$this->redirect('&route=invoices&msg=3');
	}

	public function listPricequotes()
	{
		$listPriceQuotes = $this->api->listPriceQuotes();

		if ( ! $listPriceQuotes->Error) {
			$curpage 		= (empty($_GET['p']) ? 1 : $_GET['p']);
			$pages 			= ceil($listPriceQuotes->Count / $this->limit);
			$start 			= ($curpage - 1) * $this->limit;
			$pricequotes 		= array_slice($listPriceQuotes->Result->PriceQuotes, $start, $this->limit);

			$viewData = array(
				'pricequotes' => $pricequotes,
				'pages' => $pages,
				'curpage' => $curpage
			);
		}

		$this->render('list-pricequotes', $viewData);
	}

	public function viewPricequote($id)
	{
		$getPriceQuote = $this->api->getPriceQuote($id);
		$pricequote = $getPriceQuote->Result->PriceQuote;

		$getDebtor = $this->api->getDebtor($pricequote->Debtor);
		$debtor = $getDebtor->Result->Debtor;

		$viewData = array(
			'pricequote' => $pricequote,
			'debtor' => $debtor
		);

		$this->render('view-pricequote', $viewData);
	}

	public function setPricequoteAccepted($id)
	{
		$this->api->changePriceQuoteStatus($id, 'true');
		$this->redirect('&route=pricequotes&msg=4');
	}

	public function setPricequoteDeclined($id)
	{
		$this->api->changePriceQuoteStatus($id, 'false');
		$this->redirect('&route=pricequotes&msg=5');
	}

	public function listProducts()
	{
		$listProducts = $this->api->listProducts();

		if ( ! $listProducts->Error) {
			$curpage 		= (empty($_GET['p']) ? 1 : $_GET['p']);
			$pages 			= ceil($listProducts->Count / $this->limit);
			$start 			= ($curpage - 1) * $this->limit;
			$products 		= array_slice($listProducts->Result->Products, $start, $this->limit);

			$viewData = array(
				'products' => $products,
				'pages' => $pages,
				'curpage' => $curpage
			);
		}

		$this->render('list-products', $viewData);
	}

	public function settings()
	{
		if ( ! empty( $_POST ) ) {
			update_option('wefact_url', $_POST['wefact_url']);
			update_option('wefact_key', $_POST['wefact_key']);
		}

		$this->render('settings', $viewData);
	}

	private function getTotal() {
		$listInvoices = $this->api->listInvoices();

		$total = array('revenue' => 0, 'invoices' => 0);

		if ($results = $listInvoices->Result->Invoices) {
			foreach ($results as $row) {
				$date = date('Y', strtotime($row->Date));
				if ($row->Status == 4 && $date == date('Y')) {
					$total['revenue'] += $row->AmountExcl;
					$total['invoices'] += 1;
				}
			}
		}

		$listSubscriptions = $this->api->listSubscriptions();
		if ($results = $listSubscriptions->Result->Subscriptions) {
			foreach ($results as $row) {
				$date = date('Y', strtotime($row->NextDate));
				if ($date == date('Y')) {
					$total['revenue'] += $row->PriceExcl;
				}
			}
		}
		return $total;
	}

	public function route()
	{
		foreach ($this->routes as $route => $action) {
			$routeparts = explode('/', $route);
			$match = 0;
			$vars = array();
				
			if (count($routeparts) == count($this->urlparts)) {
				for ($i = 0; $i < count($this->urlparts); $i++) {
					if ($routeparts[$i] == $this->urlparts[$i]) {
						$match += 1;
					} 
					elseif ($routeparts[$i] == '*') {
						$match += 1;
						$vars[] = $this->urlparts[$i];
					}
					if ($match == count($this->urlparts)) {
						if (method_exists($this, $action)) {
							if (empty($vars)) {
								call_user_func(array($this, $action));
							}
							else {
								call_user_func_array(array($this, $action), $vars);
							}
						}
						break;
					}
				}
			}
		}
	}

	private function showMsg()
	{
		if (isset($_GET['msg'])) {
			$tmp = wefact_messages($_GET['msg']);
			echo '<div class="'.$tmp['class'].'">'.$tmp['message'].'</div>';
		}
	}

	private function render($page, $viewData = array())
	{
		if ( ! $this->rendered) {
			if ( ! empty($viewData)) {
				extract($viewData, EXTR_SKIP);				
			}
			include_once(dirname(__FILE__).'/views/include/page_top.php');
			include_once(dirname(__FILE__).'/views/'.$page.'.php');
			include_once(dirname(__FILE__).'/views/include/page_bottom.php');
			$this->rendered = true;
		}
	}

	private function redirect($to) {
		if ( ! $this->rendered) {
			$redirectURL = get_site_url().'/wp-admin/admin.php?page=wefact'.$to;
			echo '<meta http-equiv="refresh" content="0; ' . $redirectURL . '">';
			$this->rendered = true;
			exit;
		}
	}

}

new WeFact();
?>