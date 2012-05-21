<?php
header("Content-type: text/html; charset=utf-8");

require_once(dirname(__FILE__) . '/wefact_admin.php');

/**
 * WeFactAPI
 * 
 * @author WeFact B.V.
 * @copyright 2011
 * @version 1.0
 * @access public
 */
class WeFactAPI
{
	private $client;
	private $securitykey;
	private $error;
	
	/**
	 * WeFactAPI::__construct()
	 * 
	 * @return
	 */
	function __construct(){
		try {
			$clientURL			= get_option('clientURL');
			$clientSecuritykey	= get_option('clientSecuritykey');
			
			// redirection part
			if($clientURL === '' || $clientSecuritykey === '') 
			{
				wp_redirect(admin_url('/admin.php?page=wefact/settings'));
				exit;
			}
			
			$this->client		= new SoapClient("$clientURL", array('trace' => true));
			
			$this->securitykey	= $clientSecuritykey;
			
			return true;
			
		} catch(Exception $e){
			$this->error 		= 'Exception: ' . $e->getMessage();
			return false;
		}
	}
	
	/**
	 * WeFactAPI::getDebtor()
	 * 
	 * Retrieve personal data from debtor.
	 * 
	 * @param string $security_code Security code for access to the API
	 * @param mixed $debtor_id Identifier of the debtor, can be obtained by using the listDebtors() function
	 */
	function getDebtor($debtor_id){
		return $this->client->getDebtor($this->securitykey, $debtor_id);
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
	function listDebtors($filter = array()){
		return $this->client->listDebtors($this->securitykey, $filter);	
	}
	
	/**
	 * WeFactAPI::addDebtor()
	 * 
	 * Create a new debtor in WeFact
	 * 
	 * @param string $security_code Security code for access to the API
	 * @param mixed $newDebtor Array with optional keys as mentioned in documentation
	 */
	function addDebtor($newDebtor = array()){
		return $this->client->addDebtor($this->securitykey, $newDebtor);
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
	function getDebtorID($debtor_code){
		return $this->client->getDebtorID($this->securitykey, $debtor_code);
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
	function terminateSubscription($subscription_id, $termination_date = null){
		return $this->client->terminateSubscription($this->securitykey, $subscription_id, $termination_date);
	}
	
	/**
	 * WeFactAPI::addInvoice()
	 * 
	 * Create a new invoice in WeFact
	 * 
	 * @param string $security_code Security code for access to the API
	 * @param mixed $newInvoice Array with optional keys as mentioned in documentation
	 */
	function addInvoice($newInvoice = array()){	
		return $this->client->addInvoice($this->securitykey, $newInvoice);
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
	function listInvoices($filter = array()){
		return $this->client->listInvoices($this->securitykey, $filter);
	}
	
	/**
	 * WeFactAPI::getInvoice()
	 * 
	 * Retrieve general data from invoice.
	 * 
	 * @param string $security_code Security code for access to the API
	 * @param mixed $invoice_id Identifier of the invoice, can be obtained by using the listInvoices() function
	 */
	function getInvoice($invoice_id){
		return $this->client->getInvoice($this->securitykey, $invoice_id);
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
		return $this->client->downloadInvoice($this->securitykey, $invoice_id);
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
		return $this->client->sendInvoiceByEmail($this->securitykey, $invoice_id);
	}
	
	/**
	 * WeFactAPI::changeInvoiceStatus()
	 * 
	 * Retrieve general data from invoice.
	 * 
	 * @param string $security_code Security code for access to the API
	 * @param mixed $invoice_id Identifier of the invoice, can be obtained by using the listInvoices() function
	 * @param string $paid 'true' = paid or 'false' = not paid
	 */
	function changeInvoiceStatus($invoice_id, $paid){
		return $this->client->changeInvoiceStatus($this->securitykey, $invoice_id, $paid);
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
	function listPriceQuotes($filter = array()){
		return $this->client->listPriceQuotes($this->securitykey, $filter);	
	}
	
	/**
	 * WeFactAPI::getPriceQuote()
	 * 
	 * @param mixed $security_code
	 * @param mixed $pricequote_id
	 * @return
	 */
	function getPriceQuote($pricequote_id){
		return $this->client->getPriceQuote($this->securitykey, $pricequote_id);
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
	function changePriceQuoteStatus($pricequote_id, $accept, $makeinvoice = 'false'){
		return $this->client->changePriceQuoteStatus($this->securitykey, $pricequote_id, $accept, $makeinvoice);
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
	function listProducts($filter = array()){
		return $this->client->listProducts($this->securitykey, $filter);	
	}

}
?>