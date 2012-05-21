<?php

require_once(dirname(__FILE__) . '/api.php');
require_once(dirname(__FILE__) . '/wefact_admin.php');

if(!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Wefact_Dashboard_Link_List_Table extends WP_List_Table
{

	/**
	* Constructor, we override the parent to pass our own arguments
	* We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
	*/
	function __construct() 
	{
		parent::__construct( array(
			'singular'	=> 'dashboard_invoice', //Singular label
			'plural'	=> 'dashboard_invoices', //plural label, also this well be one of the table css class
			'ajax'		=> false //We won't support Ajax for this table
		));
	}
	
	
	/** ************************************************************************
     * Recommended. This method is called when the parent class can't find a method
     * specifically build for a given column. Generally, it's recommended to include
     * one method for each column you want to render, keeping your package class
     * neat and organized. For example, if the class needs to process a column
     * named 'title', it would first see if a method named $this->column_title() 
     * exists - if it does, that method will be used. If it doesn't, this one will
     * be used. Generally, you should try to use custom column methods as much as 
     * possible. 
     * 
     * Since we have defined a column_title() method later on, this method doesn't
     * need to concern itself with any column with a name of 'title'. Instead, it
     * needs to handle everything else.
     * 
     * For more detailed insight into how columns are handled, take a look at 
     * WP_List_Table::single_row_columns()
     * 
     * @param array $item A singular item (one full row's worth of data)
     * @param array $column_name The name/slug of the column to be processed
     * @return string Text or HTML to be placed inside the column <td>
     **************************************************************************/
   
	function column_default($item, $column_name) 
	{
        switch($column_name){
			case 'InvoiceCode':
			case 'CompanyName':
			case 'AmountExcl':
			case 'AmountIncl':
			case 'Date':
				return $item[$column_name];
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }
	
	function column_Contact($item)
	{
		return $item['Initials'] . ' ' . $item['SurName'];
	}
	
	function column_Status($item)
	{
		switch($item['Status'])
		{
			case 0: return __('Concept', 'wefact');
			case 2: return __('Sent', 'wefact');
			case 3: return __('Partly paid', 'wefact');
			case 4: return __('Paid', 'wefact');
			case 8: return __('Credit invoice', 'wefact');
			case 9: return __('Expired', 'wefact');
		}
	}
	
	/** ************************************************************************
     * REQUIRED! This method dictates the table's columns and titles. This should
     * return an array where the key is the column slug (and class) and the value 
     * is the column's title text. If you need a checkbox for bulk actions, refer
     * to the $columns array below.
     * 
     * The 'cb' column is treated differently than the rest. If including a checkbox
     * column in your table you must create a column_cb() method. If you don't need
     * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
     * 
     * @see WP_List_Table::::single_row_columns()
     * @return array An associative array containing column information: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_columns() 
	{
		$columns = array(
			'InvoiceCode'	=> __('InvoiceCode', 'wefact'),
			'CompanyName'	=> __('CompanyName', 'wefact'),
			'Contact'		=> __('Contact', 'wefact'),
			'AmountExcl'	=> __('AmountExcl', 'wefact'),
			'AmountIncl'	=> __('AmountIncl', 'wefact'),
			'Date'			=> __('Date', 'wefact'),
			'Status'		=> __('Status', 'wefact')
        );
        return $columns;
    }
	
	function column_InvoiceCode($item) 
	{
        $api = new WeFactAPI();
		
		$invoice_code 	= 'InvoiceCode';
		$invoice_info 	= $api->getInvoiceID($invoice_code);

		//Build row actions
        $actions = array(
			'view'			=> sprintf('<a href="admin.php?page=wefact&action=%s&invoice=%s">'.__('View', 'wefact').'</a>','view',$item['InvoiceCode']),
        );
		
		return sprintf('%1$s %2$s', $item['InvoiceCode'], $this->row_actions($actions));
	}
	
	function no_items() 
	{
		_e( 'If you are expecting to see items and there are none, please check your site url and the security key.', 'wefact');
	}
	
	public function prepare_items() 
	{
		$columns = $this->get_columns();

        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        
        /**
         * Instead of querying a database, we're going to fetch the example data
         * property we created for use in this plugin. This makes this example 
         * package slightly different than one you might build on your own. In 
         * this example, we'll be using array manipulation to sort and paginate 
         * our data. In a real-world implementation, you will probably want to 
         * use sort and pagination data to build a custom query instead, as you'll
         * be able to use your precisely-queried data immediately.
         */
		
		$api = new WeFactAPI();
		
		// List invoices
		$parameters	= array(
					"Sort" 		=> "Date",
					"Order" 	=> "DESC",
					"Search" 	=> "",
        );
		
		$obj = $api->listInvoices($parameters)->Result->Invoices;
		$obj = array_slice($obj, 0, 5);
		$data = object_to_array($obj);
		
		
        /**
         * This checks for sorting input and sorts the data in our array accordingly.
         * 
         * In a real-world situation involving a database, you would probably want 
         * to handle sorting by passing the 'orderby' and 'order' values directly 
         * to a custom query. The returned data will be pre-sorted, and this array
         * sorting technique would be unnecessary.
         */
		function usort_reorder($a, $b) 
		{	
			$orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'Date'; //If no sort, default to title
			$order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc'; //If no order, default to asc
			$result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order 
			return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
		}
		usort($data, 'usort_reorder');
		
        /**
         * REQUIRED for pagination. Let's check how many items are in our data array. 
         * In real-world use, this would be the total number of items in your database, 
         * without filtering. We'll need this later, so you should always include it 
         * in your own package classes.
         */
        
                
        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently 
         * looking at. We'll need this later, so you should always include it in 
         * your own package classes.
         */
        
        
        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to 
         */
        
        
        /**
         * REQUIRED. Now we can add our *sorted* data to the items property, where 
         * it can be used by the rest of the class.
         */
         $this->items = $data;
	
        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */

	}  
}	
	/***************************** RENDER TEST PAGE ********************************
	*******************************************************************************
	* This function renders the admin page and the example list table. Although it's
	* possible to call prepare_items() and display() from the constructor, there
	* are often times where you may need to include logic here between those steps,
	* so we've instead called those methods explicitly. It keeps things flexible, and
	* it's the way the list tables are used in the WordPress core.
	*/
	function wefact_render_dashboard_invoice_list_page() 
	{

		//Create an instance of our package class...
		$testListTable = new Wefact_Dashboard_Link_List_Table();
		//Fetch, prepare, sort, and filter our data...
		$testListTable->prepare_items();

		?>
		<div class="wrap">

			<h2><?php echo _e('WeFact', 'wefact'); ?> | <?php echo _e('Dashboard invoices list', 'wefact'); ?></h2>

			<div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
			</div>

			<!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
			<form id="invoice-filter" method="get">
				<!-- For plugins, we also need to ensure that the form posts back to our current page -->
				<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
				<!-- Now we can render the completed list table -->
				<?php $testListTable->display(); ?>

			</form>

		</div>
    <?php
	}
