<?php

class WPWF_107 {
	public static $error = array( 'Status' => 'error', 'Error' => 'Er kon geen verbinding worden gemaakt met Wefact.' );
	public static $wpwf_api_key = '';
	private static $client;


	static function object_to_array($obj) {
       	$arrObj = is_object($obj) ? get_object_vars($obj) : $obj;
        foreach ($arrObj as $key => $val) {
                $val = (is_array($val) || is_object($val)) ? self::object_to_array($val) : $val;
                $arr[$key] = $val;
        }
        return $arr;
	}

	static function construct( $wpwf_api_url = false, $wpwf_api_key = false ) {
		
		if( $wpwf_api_key ): self::$wpwf_api_key = $wpwf_api_key; endif;
		
		if( class_exists( 'SoapClient' ) ):
			if( self::$client ):
				return self::$client;
			else:
				try {
					self::$client = @new SoapClient( $wpwf_api_url );
					return self::$client;
				} catch(Exception $e) {
					$client		= null;
					$error 		= 'Exception: ' . $e->getMessage();
					return false;
				}
			endif;
		else:
			return false;
		endif;
	}

	static function getDebtor( $debtor_id ){
		if( self::$client ) { return self::object_to_array( self::$client->getDebtor( self::$wpwf_api_key, $debtor_id ) );
		} else { return self::$error; }
	}
	
	static function listDebtors( $filter = array() ){
		if( self::$client ) { return self::object_to_array( self::$client->listDebtors( self::$wpwf_api_key, $filter ) );
		} else { return self::$error; }
	}
	
	static function addDebtor( $newDebtor = array()){
		if( self::$client ) { return self::object_to_array( self::$client->addDebtor( self::$wpwf_api_key, $newDebtor ) );
		} else { return self::$error; }
	}
	
	static function editDebtor( $debtor_id, $editDebtor = array()){
		if( self::$client ) { return self::object_to_array( self::$client->editDebtor( self::$wpwf_api_key, $debtor_id, $editDebtor ) );
		} else { return self::$error; }
	}
	
	static function deleteDebtor( $debtor_id, $remove_subscriptions = false){
		if( self::$client ) { return self::object_to_array( self::$client->deleteDebtor( self::$wpwf_api_key, $debtor_id, $remove_subscriptions ) );
		} else { return self::$error; }
	}
	
	static function getDebtorID( $debtor_code ){
		if( self::$client ) { return self::object_to_array( self::$client->getDebtorID( self::$wpwf_api_key, $debtor_code ) );
		} else { return self::$error; }
	}
	
	static function checkLoginDebtor( $username, $password){
		if( self::$client ) { return self::object_to_array( self::$client->checkLoginDebtor( self::$wpwf_api_key, $username, $password ) );
		} else { return self::$error; }
	}
	
	static function addSubscription( $newSubscription = array()){
		if( self::$client ) { return self::object_to_array( self::$client->addSubscription( self::$wpwf_api_key, $newSubscription ) );
		} else { return self::$error; }
	}

	static function listSubscriptions( $filter = array()){
		if( self::$client ) { return self::object_to_array( self::$client->listSubscriptions( self::$wpwf_api_key, $filter ) );
		} else { return self::$error; }		
	}
	
	static function terminateSubscription( $subscription_id, $termination_date = null, $agenda_item = false){
		if( self::$client ) { return self::object_to_array( self::$client->terminateSubscription( self::$wpwf_api_key, $subscription_id, $termination_date, $agenda_item ) );
		} else { return self::$error; }		
	}
	
	static function addInvoice( $newInvoice = array()){	
		if( self::$client ) { return self::object_to_array( self::$client->addInvoice( self::$wpwf_api_key, $newInvoice ) );
		} else { return self::$error; }
	}
	
	static function addInvoiceLine( $invoice_id, $newInvoiceLine = array()){	
		if( self::$client ) { return self::object_to_array( self::$client->addInvoiceLine( self::$wpwf_api_key, $invoice_id, $newInvoiceLine ) );
		} else { return self::$error; }
	}
	
	static function listInvoices( $filter = array()){
		if( self::$client ) { return self::object_to_array( self::$client->listInvoices( self::$wpwf_api_key, $filter ) );
		} else { return self::$error; }		
	}
	
	static function getInvoice($invoice_id){
		if( self::$client ) { return self::object_to_array( self::$client->getInvoice( self::$wpwf_api_key, $invoice_id ) );
		} else { return self::$error; }
	}
	
	static function getInvoiceID( $invoice_id){
		if( self::$client ) { return self::object_to_array( self::$client->getInvoiceID( self::$wpwf_api_key, $invoice_id ) );
		} else { return self::$error; }
	}
	
	static function downloadInvoice( $invoice_id){
		if( self::$client ) { return self::object_to_array( self::$client->downloadInvoice( self::$wpwf_api_key, $invoice_id ) );
		} else { return self::$error; }
	}
	
	static function sendInvoiceByEmail( $invoice_id){
		if( self::$client ) { return self::object_to_array( self::$client->sendInvoiceByEmail( self::$wpwf_api_key, $invoice_id ) );
		} else { return self::$error; }
	}
	
	static function changeInvoiceStatus( $invoice_id, $paid, $date = ''){
		if( self::$client ) { return self::object_to_array( self::$client->changeInvoiceStatus( self::$wpwf_api_key, $invoice_id, $paid, $date ) );
		} else { return self::$error; }
	}
	
	static function addPriceQuote( $newPriceQuote = array()){
		if( self::$client ) { return self::object_to_array( self::$client->addPriceQuote( self::$wpwf_api_key, $newPriceQuote ) );
		} else { return self::$error; }
	}
	
	static function listPriceQuotes( $filter = array()){
		if( self::$client ) { return self::object_to_array( self::$client->listPriceQuotes( self::$wpwf_api_key, $filter ) );
		} else { return self::$error; }
	}
	
	static function getPriceQuote( $pricequote_id){
		if( self::$client ) { return self::object_to_array( self::$client->getPriceQuote( self::$wpwf_api_key, $pricequote_id ) );
		} else { return self::$error; }
	}
	
	static function getPriceQuoteID( $pricequote_code ){
		if( self::$client ) { return self::object_to_array( self::$client->getPriceQuoteID( self::$wpwf_api_key, $pricequote_code ) );
		} else { return self::$error; }
	}

	static function downloadPriceQuote( $pricequote_id ){
		if( self::$client ) { return self::object_to_array( self::$client->downloadPriceQuote( self::$wpwf_api_key, $pricequote_id ) );
		} else { return self::$error; }
	}
	
	static function sendPriceQuoteByEmail( $pricequote_id ){
		if( self::$client ) { return self::object_to_array( self::$client->sendPriceQuoteByEmail( self::$wpwf_api_key, $pricequote_id ) );
		} else { return self::$error; }
	}
	
	static function changePriceQuoteStatus( $pricequote_id, $accept, $makeinvoice = 'false'){
		if( self::$client ) { return self::object_to_array( self::$client->changePriceQuoteStatus( self::$wpwf_api_key, $pricequote_id, $accept, $makeinvoice ) );
		} else { return self::$error; }
	}
	
	static function getProduct( $product_id ){
		if( self::$client ) { return self::object_to_array( self::$client->getProduct( self::$wpwf_api_key, $product_id ) );
		} else { return self::$error; }
	}
	
	static function listProducts( $filter = array() ){
		if( self::$client ) { return self::object_to_array( self::$client->listProducts( self::$wpwf_api_key, $filter ) );
		} else { return self::$error; }
	}
}
?>