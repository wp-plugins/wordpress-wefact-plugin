<?php
class WeFact {

	private $routes  = array(
		'dashboard' => 'viewDashboard',
		'debtors' => 'listDebtors',
		'debtors/view/*' => 'viewDebtor',
		'invoices' => 'listInvoices',
		'invoices/view/*' => 'viewInvoice',
		'invoices/paid/*' => 'setInvoicePaid',
		'pricequotes' => 'listPricequotes',
		'pricequotes/view/*' => 'viewPricequote',
		'pricequotes/accepted/*' => 'setPricequoteAccepted',
		'pricequotes/declined/*' => 'setPricequoteDeclined',
		'products' => 'listProducts',
		'settings' => 'settings'
	);
	
	private $tabs = array();

	private $urlparts = array();

	private $rendered = false;

	public function __construct()
	{
		$this->tabs = array(
			'dashboard' 	=> __('Dashboard', 'wp_wefact'),
			'debtors' 		=> __('Debtors', 'wp_wefact'),
			'invoices' 		=> __('Invoices', 'wp_wefact'),
			'pricequotes'	=> __('Pricequotes', 'wp_wefact'),
			'products'		=> __('Products', 'wp_wefact'),
			'settings'		=> __('Settings', 'wp_wefact')
		);

		$this->urlparts = explode('/', (empty($_GET['route']) ? 'dashboard' : $_GET['route']));
	}

	public function adminMenu()
	{
		add_menu_page(
			'WeFact',
			'WeFact',
			'manage_options',
			'wefact',
			array(&$this, 'route'),
			plugins_url('wordpress-wefact-plugin/images/favicon.ico')
		);
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

	public function route()
	{
		$WeFactAdmin = new WeFactAdmin();

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
						if (method_exists($WeFactAdmin, $action)) {
							if (empty($vars)) {
								call_user_func(array($WeFactAdmin, $action));
							}
							else {
								call_user_func_array(array($WeFactAdmin, $action), $vars);
							}
						}
						break;
					}
				}
			}
		}
	}

	protected function showMsg()
	{
		if (isset($_GET['msg'])) {
			$tmp = wefact_messages($_GET['msg']);
			echo '<div class="'.$tmp['class'].'">'.$tmp['message'].'</div>';
		}
	}

	protected function render($page, $viewData = array())
	{
		if ( ! $this->rendered) {
			if ( ! empty($viewData)) {
				extract($viewData, EXTR_SKIP);				
			}
			include_once(dirname(__FILE__).'/../views/include/page_top.php');
			include_once(dirname(__FILE__).'/../views/'.$page.'.php');
			include_once(dirname(__FILE__).'/../views/include/page_bottom.php');
			$this->rendered = true;
		}
		
	}

	protected function redirect($to) {
		if ( ! $this->rendered) {
			$redirectURL = get_site_url().'/wp-admin/admin.php?page=wefact'.$to;
			echo '<meta http-equiv="refresh" content="0; ' . $redirectURL . '">';
			$this->rendered = true;
			exit;
		}
	}

}
?>