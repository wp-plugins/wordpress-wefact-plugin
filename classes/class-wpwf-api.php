<?php
header("Content-type: text/html; charset=utf-8");
/**
 * WeFactAPI
 * 
 * @author WeFact B.V.
 * @copyright 2011
 * @version 1.0
 * @access public
 */
class WPWF_API {

	public $client;
	public $connection;

	private $old_api;
	private $wpwf_api_key;
	private $error;

	/**
	 * WeFactAPI::__construct()
	 * 
	 * @return
	 */
	function __construct( $wpwf_api_url, $wpwf_api_key, $wpwf_api_type, $wpwf_api_version ) {
		$this->wpwf_api_key 	= $wpwf_api_key;
		if( $wpwf_api_type == 'hosting' && $wpwf_api_version == '2.00' ):
			$this->old_api = false; $this->connection = true;
			require_once( dirname(__FILE__) . '/class-api-200.php' );
			WPWF_200::SetData($wpwf_api_url, $wpwf_api_key);
		else:
			$this->old_api = true;
			require_once( dirname(__FILE__) . '/class-api-107.php' );
			if( WPWF_107::construct($wpwf_api_url, $wpwf_api_key) ): $this->connection = true; else: $this->connection = false; endif;
		endif;

		$this->check_connection();
	}
	
	private function check_connection() {
		if( $this->connection && (isset($_POST['wpwf_api_key']) || isset($_POST['wc_wefact_key']))  ):
			$result = self::listInvoices();
			if( isset($_POST['wpwf_api_key']) ): $type = 'wpwf'; elseif( $_POST['wc_wefact_key'] ): $type = 'wcwf'; endif;

			if( $result['status'] == 'success' ):
				$this->connection = true; update_option($type.'_active', '1');
			else:
				$this->connection = false; update_option($type.'_active', '0');
			endif;
		endif;
	}

	public function getDebtor($debtor_id) {
		if( $this->old_api ):
			$result = WPWF_107::getDebtor($debtor_id);
		else:
			$result = WPWF_200::Request('debtor', 'show', array('Identifier' => $debtor_id));
		endif;
		return $this->Results( $result, 'debtor');
	}
	
	/**
	 * WeFactAPI::listDebtors()
	 * 
	 * List all debtors available in WeFact.
	 * Sort & Order arguments for sorting the results
	 * Search argument for searching in fields DebtorCode, CompanyName and SurName.
	 * 
	 * @param string $security_code Security code for access to the API
	 * @param mixed $filter Array with optional keys Sort (e.g. DebtorCode), Order (e.g. ASC) and Search (e.g. WeFact).
	 */
	function listDebtors($filter = array()) {
		if( $this->old_api ):
			$result = WPWF_107::listDebtors($filter);
		else:
			$result = WPWF_200::Request('debtor', 'list', array_change_key_case($filter));
		endif;
		return $this->Results( $result, 'debtors');
	}
	
	/**
	 * WeFactAPI::addDebtor()
	 * 
	 * Create a new debtor in WeFact
	 * 
	 * @param string $security_code Security code for access to the API
	 * @param mixed $newDebtor Array with optional keys as mentioned in documentation
	 */
	function addDebtor($data = array()) {
		if( $this->old_api ):
			$result = WPWF_107::addDebtor($data);
		else:
			$result = WPWF_200::Request('debtor', 'add', array_change_key_case($data));
		endif;
		return $this->Results( $result, 'debtor' );
	}
	
	/**
	 * WeFactAPI::editDebtor()
	 * 
	 * Edit a debtor in WeFact
	 * 
	 * @param string $security_code Security code for access to the API
	 * @param int $debtor_id Debtor identifier
	 * @param mixed $editDebtor Array with optional keys as mentioned in documentation
	 */
	function editDebtor($debtor_id, $editDebtor = array()){
		return $this->client->editDebtor($this->securitykey, $debtor_id, $editDebtor);
	}
	
	/**
	 * WeFactAPI::deleteDebtor()
	 * 
	 * Delete a debtor in WeFact
	 * 
	 * @param mixed $security_code
	 * @param mixed $debtor_id
	 * @return
	 */
	function deleteDebtor($debtor_id, $remove_subscriptions = false){
		return $this->client->deleteDebtor($this->securitykey, $debtor_id, $remove_subscriptions);
	}
	
	/**
	 * WeFactAPI::getDebtorID()
	 * 
	 * Retrieve ID from debtor
	 * 
	 * @param mixed $security_code
	 * @param mixed $debtor_code
	 * @return
	 */
	function getDebtorID($debtor_code) {
		if( $this->old_api ):
			$result = WPWF_107::getDebtorID($debtor_code);
			$result['Result']['Identifier'] = (isset($result['Result']['Value']) ? $result['Result']['Value'] : false);
		else:
			$result = WPWF_200::Request('debtor', 'show', array( 'DebtorCode' => $debtor_code ) );
			$result['identifier'] = (isset($result['debtor']['Identifier']) ? $result['debtor']['Identifier'] : false);
		endif;

		return $this->Results( $result, 'Identifier');
	}
	
	/**
	 * WeFactAPI::checkLoginDebtor()
	 * 
	 * Check login credentials
	 * 
	 * @param mixed $security_code
	 * @param mixed $username
	 * @param mixed $password
	 * @return
	 */
	function checkLoginDebtor($username, $password){
		return $this->client->checkLoginDebtor($this->securitykey, $username, $password);
	}
	
	/**
	 * WeFactAPI::addSubscription()
	 * 
	 * Create a new subscription in WeFact
	 * 
	 * @param string $security_code Security code for access to the API
	 * @param mixed $newSubscription Array with optional keys as mentioned in documentation
	 */
	function addSubscription($newSubscription = array()){
		return $this->client->addSubscription($this->securitykey, $newSubscription);
	}

	function getSubscription($subscription_id) {
		if( !$this->old_api ):
			$result = WPWF_200::Request('subscription', 'show', array( 'Identifier' => $subscription_id ));
		endif;
		return $this->Results( $result, 'subscription');
	}

	/**
	 * WeFactAPI::listSubscriptions()
	 * 
	 * Get a list of active subscriptions
	 * 
	 * @param mixed $security_code
	 * @param mixed $filter
	 * @return
	 */
	function listSubscriptions($filter = array()) {
		if( $this->old_api ):
			$result = WPWF_107::listSubscriptions($filter);
		else:
			$result = WPWF_200::Request('subscription', 'list', array_change_key_case($filter, CASE_LOWER));
		endif;
		return $this->Results( $result, 'subscriptions');
	}
	
	/**
	 * WeFactAPI::terminateSubscription()
	 * 
	 * Terminate a subscription
	 * 
	 * @param mixed $security_code
	 * @param mixed $subscription_id
	 * @param mixed $termination_date
	 * @return
	 */
	function terminateSubscription($subscription_id, $termination_date = null, $agenda_item = false){
		return $this->client->terminateSubscription($this->securitykey, $subscription_id, $termination_date, $agenda_item);
	}
	
	/**
	 * WeFactAPI::addInvoice()
	 * 
	 * Create a new invoice in WeFact
	 * 
	 * @param string $security_code Security code for access to the API
	 * @param mixed $newInvoice Array with optional keys as mentioned in documentation
	 */
	function addInvoice($data = array()) {
		if( $this->old_api ):
			$result = WPWF_107::addInvoice($data);
		else:
			$result = WPWF_200::Request('invoice', 'add', array_change_key_case($data));
		endif;
		return $this->Results( $result, 'invoice' );
	}
	
	/**
	 * WeFactAPI::addInvoiceLine()
	 * 
	 * Add an extra invoice line to an existing invoice
	 * 
	 * @param mixed $security_code
	 * @param mixed $invoice_id
	 * @param mixed $newInvoiceLine
	 * @return
	 */
	function addInvoiceLine($invoice_id, $newInvoiceLine = array()){	
		return $this->client->addInvoiceLine($this->securitykey, $invoice_id, $newInvoiceLine);
	}
		
	/**
	 * WeFactAPI::getError()
	 * 
	 * @return
	 */
	function getError(){
		return $this->error;
	}
	
	/**
	 * WeFactAPI::listInvoices()
	 * 
	 * List all invoices available in WeFact.
	 * Sort & Order arguments for sorting the results
	 * Search argument for searching in fields InvoiceCode, CompanyName and SurName
	 * 
	 * @param string $security_code Security code for access to the API
	 * @param mixed $filter Array with optional keys Sort (e.g. InvoiceCode), Order (e.g. ASC) and Search (e.g. WeFact).
	 */
	function listInvoices( $filter = array() ) {
		if( $this->old_api ):
			$result = WPWF_107::listInvoices($filter);
		else:
			if( isset($filter['Debtor']) ):
				$filter['searchat'] = 'Debtor';
				$filter['searchfor'] = $filter['Debtor'];
				unset($filter['Debtor']);
			endif;
			$result = WPWF_200::Request('invoice', 'list', array_change_key_case($filter, CASE_LOWER) );
		endif;
		return $this->Results( $result, 'invoices');
	}
	
	/**
	 * WeFactAPI::getInvoice()
	 * 
	 * Retrieve general data from invoice.
	 * 
	 * @param string $security_code Security code for access to the API
	 * @param mixed $invoice_id Identifier of the invoice, can be obtained by using the listInvoices() function
	 */
	function getInvoice( $invoice_id ) {
		if( $this->old_api ):
			$result = WPWF_107::getInvoice($invoice_id);
		else:
			$result = WPWF_200::Request('invoice', 'show',  array('Identifier' => $invoice_id) );
		endif;
		return $this->Results( $result, 'invoice' );
	}
	
	/**
	 * WeFactAPI::getInvoiceID()
	 * 
	 * Retrieve general data from invoice.
	 * 
	 * @param string $security_code Security code for access to the API
	 * @param mixed $invoice_id Identifier of the invoice, can be obtained by using the listInvoices() function
	 */
	function getInvoiceID($invoice_id){
		return $this->client->getInvoiceID($this->securitykey, $invoice_id);
	}
	
	/**
	 * WeFactAPI::downloadInvoice()
	 * 
	 * Download PDF file
	 * 
	 * @param string $security_code Security code for access to the API
	 * @param mixed $invoice_id Identifier of the invoice, can be obtained by using the listInvoices() function
	 */
	function downloadInvoice($invoice_id){
		if( $this->old_api ):
			$result = WPWF_107::downloadInvoice($invoice_id);
		else:
			$result = WPWF_200::Request('invoice', 'download', array('Identifier' => $invoice_id));
			$result['pdf'] = (isset($result['invoice']['Base64']) ? $result['invoice']['Base64'] : '');
		endif;
		return $this->Results( $result, 'PDF');
	}
	
	/**
	 * WeFactAPI::sendInvoice()
	 * 
	 * Send invoice via e-mail
	 * 
	 * @param string $security_code Security code for access to the API
	 * @param mixed $invoice_id Identifier of the invoice, can be obtained by using the listInvoices() function
	 */
	function sendInvoiceByEmail($invoice_id){
		if( $this->old_api ):
			$result = WPWF_107::sendInvoiceByEmail($invoice_id);
		else:
			$result = WPWF_200::Request('invoice', 'sendbyemail', array('Identifier' => $invoice_id));
		endif;
		return $this->Results( $result, 'invoice');
	}
	
	/**
	 * WeFactAPI::changeInvoiceStatus()
	 * 
	 * Retrieve general data from invoice.
	 * 
	 * @param string $security_code Security code for access to the API
	 * @param mixed $invoice_id Identifier of the invoice, can be obtained by using the listInvoices() function
	 * @param string $paid 'true' = paid or 'false' = not paid
	 * @param string $date You can parse the paydate
	 */
	function changeInvoiceStatus($invoice_id, $paid, $date = '') {
		if( $this->old_api ):
			$result = WPWF_107::changeInvoiceStatus($invoice_id, $paid);
		else:
			if( $paid == 'true' ):
				$result = WPWF_200::Request('invoice', 'markaspaid', array('Identifier' => $invoice_id));
			endif;
		endif;
		return $this->Results( $result, 'invoice');
	}
	
	/**
	 * WeFactAPI::addPriceQuote()
	 * 
	 * @param mixed $security_code
	 * @param mixed $newPriceQuote
	 * @return
	 */
	function addPriceQuote($newPriceQuote = array()){
		return $this->client->addPriceQuote($this->securitykey, $newPriceQuote);
	}
	
	/**
	 * WeFactAPI::listPriceQuotes()
	 * 
	 * @param mixed $security_code
	 * @param mixed $filter
	 * @return
	 */
	function listPriceQuotes($filter = array()) {
		if( $this->old_api ):
			$result = WPWF_107::listPriceQuotes($filter);
		else:
			if( isset($filter['Debtor']) ):
				$filter['searchat'] = 'Debtor';
				$filter['searchfor'] = $filter['Debtor'];
				unset($filter['Debtor']);
			endif;
			$result = WPWF_200::Request('pricequote', 'list', array_change_key_case($filter, CASE_LOWER));
		endif;
		return $this->Results( $result, 'priceQuotes');
	}
	
	/**
	 * WeFactAPI::getPriceQuote()
	 * 
	 * @param mixed $security_code
	 * @param mixed $pricequote_id
	 * @return
	 */
	function getPriceQuote($pricequote_id) {
		if( $this->old_api ):
			$result = WPWF_107::getPriceQuote($pricequote_id);
		else:
			$result = WPWF_200::Request('pricequote', 'show', array('Identifier' => $pricequote_id));
		endif;
		return $this->Results( $result, 'priceQuote');
	}
	
	/**
	 * WeFactAPI::getPriceQuoteID()
	 * 
	 * @param mixed $security_code
	 * @param mixed $pricequote_code
	 * @return
	 */
	function getPriceQuoteID($pricequote_code){
		return $this->client->getPriceQuoteID($this->securitykey, $pricequote_code);
	}
	
	/**
	 * WeFactAPI::downloadPriceQuote()
	 * 
	 * @param mixed $security_code
	 * @param mixed $pricequote_id
	 * @return
	 */
	function downloadPriceQuote($pricequote_id){
		return $this->client->downloadPriceQuote($this->securitykey, $pricequote_id);
	}
	
	/**
	 * WeFactAPI::sendPriceQuote()
	 * 
	 * Send pricequote via e-mail
	 * 
	 * @param string $security_code Security code for access to the API
	 * @param mixed $pricequote_id Identifier of the pricequote, can be obtained by using the listPriceQuotes() function
	 */
	function sendPriceQuoteByEmail($pricequote_id){
		return $this->client->sendPriceQuoteByEmail($this->securitykey, $pricequote_id);
	}
	
	/**
	 * WeFactAPI::changePriceQuoteStatus()
	 * 
	 * @param mixed $security_code
	 * @param mixed $pricequote_id
	 * @param mixed $accept
	 * @param mixed $makeinvoice
	 * @return
	 */
	function changePriceQuoteStatus($pricequote_id, $accept, $makeinvoice = 'false') {
		if( $this->old_api ):
			$result = WPWF_107::changePriceQuoteStatus($pricequote_id, $accept, $makeinvoice);
		else:
			if( $accept == 'true' ):
				$result = WPWF_200::Request('pricequote', 'accept', array('Identifier' => $pricequote_id));
			elseif( $accept == 'false' ):
				$result = WPWF_200::Request('pricequote', 'decline', array('Identifier' => $pricequote_id));
			endif;
		endif;
		return $this->Results( $result, 'products');
	}
	
	/**
	 * WeFactAPI::getProduct()
	 * 
	 * Retrieve general data from product
	 * 
	 * @param mixed $security_code
	 * @param mixed $product_id
	 * @return
	 */
	function getProduct($product_id){
		return $this->client->getProduct($this->securitykey, $product_id);
	}
	
	/**
	 * WeFactAPI::listProducts()
	 * 
	 * List all products available in WeFact.
	 * Sort & Order arguments for sorting the results
	 * Search argument for searching in fields ProductCode, ProductName, ProductKeyPhrase, ProductDescription
	 * 
	 * @param mixed $security_code
	 * @param mixed $filter
	 * @return
	 */
	function listProducts( $filter = array() ) {
		if( $this->old_api ):
			$result = WPWF_107::listProducts($filter);
		else:
			$result = WPWF_200::Request('product', 'list', array_change_key_case($filter, CASE_LOWER));
		endif;
		return $this->Results( $result, 'products');
	}

	function Results( $data, $type = '' ) {
		$result = array( 'result' => array(), 'count' => 0 );
		if( $this->old_api ):
			if( isset( $data['Result'][ucfirst($type)] ) ):
				$result['count'] 	= $data['Count'];
				$result['result'] 	= $data['Result'][ucfirst($type)];
				$result['status'] 	= $data['Status'];
			endif;
		else:
			if( isset( $data[strtolower($type)] ) ):
				$result['result'] = $data[strtolower($type)];
				$result['status'] = $data['status'];
			endif;
		endif;

		return $result;
	}

}
?>