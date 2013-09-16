<?php
class WeFactAdmin extends WeFact {

	protected $api;
	private $limit = 10;

	public function __construct()
	{
		parent::__construct();
		
		$wefact_url	= get_option('wefact_url');
		$wefact_key	= get_option('wefact_key');

		if( ! class_exists('SoapClient') ) {
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
}
?>