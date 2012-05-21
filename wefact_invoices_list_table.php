<?php

/* == NOTICE ===================================================================
 * Please do not alter this file. Instead: make a copy of the entire plugin, 
 * rename it, and work inside the copy. If you modify this plugin directly and 
 * an update is released, your changes will be lost!
 * ========================================================================== */

/*************************** LOAD THE BASE CLASS *******************************
 *******************************************************************************
 * The WP_List_Table class isn't automatically available to plugins, so we need
 * to check if it's available and load it if necessary.
 */
require_once(dirname(__FILE__) . '/api.php');
require_once(dirname(__FILE__) . '/wefact_admin.php');

if(!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


/************************** CREATE A PACKAGE CLASS *****************************
 *******************************************************************************
 * Create a new list table package that extends the core WP_List_Table class.
 * WP_List_Table contains most of the framework for generating the table, but we
 * need to define and override some methods so that our data can be displayed
 * exactly the way we need it to be.
 * 
 * To display this example on a page, you will first need to instantiate the class,
 * then call $yourInstance->prepare_items() to handle any data manipulation, then
 * finally call $yourInstance->display() to render the table to the page.
 * 
 * Our theme for this list table is going to be movies.
 */


class WeFact_Invoices_List_Table extends WP_List_Table 
{
    
    /** ************************************************************************
     * Normally we would be querying data from a database and manipulating that
     * for use in your list table. For this example, we're going to simplify it
     * slightly and create a pre-built array. Think of this as the data that might
     * be returned by $wpdb->query().
     *  
     **************************************************************************/

//		var $example_data = array(
//            array(
//				'Identifier'			=>	1,
//				'Date'			=> '1',
//				'Debtor'		=> '2',
//				'DebtorCode'	=> '3',
//				'AmountExcl'	=> '4',
//				'AmountIncl'	=> '5',
//				'CompanyName'	=> '6',
//				'Initials'		=> '7',
//				'SurName'		=> '8',
//				'Status'		=> '9'
//            ),
//			array(
//				'Identifier'			=>	2,
//				'Date'			=> '1',
//				'Debtor'		=> '2',
//				'DebtorCode'	=> '3',
//				'AmountExcl'	=> '4',
//				'AmountIncl'	=> '5',
//				'CompanyName'	=> '6',
//				'Initials'		=> '7',
//				'SurName'		=> '8',
//				'Status'		=> '9'
//            )
//		);
     
		
    /** ************************************************************************
     * REQUIRED. Set up a constructor that references the parent constructor. We 
     * use the parent reference to set some default configs.
     ***************************************************************************/
    function __construct() 
	{
		global $status, $page;
        
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'invoice',     //singular name of the listed records
            'plural'    => 'invoices',    //plural name of the listed records
            'ajax'      => true        //does this table support ajax?
        ) );   
    }
	
	/**
	* Add extra markup in the toolbars before or after the list
	* @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list
	*/
	function extra_tablenav( $which ) 
	{
		if ( $which == "top" ){
			//The code that goes before the table is here
		}
		if ( $which == "bottom" ){
			//The code that goes after the table is there
		}
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
     * Recommended. This is a custom column method and is responsible for what
     * is rendered in any column with a name/slug of 'title'. Every time the class
     * needs to render a column, it first looks for a method named 
     * column_{$column_title} - if it exists, that method is run. If it doesn't
     * exist, column_default() is called instead.
     * 
     * This example also illustrates how to implement rollover actions. Actions
     * should be an associative array formatted as 'slug'=>'link html' - and you
     * will need to generate the URLs yourself. You could even ensure the links
     * 
     * 
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/	
	function column_InvoiceCode($item) 
	{
        $api = new WeFactAPI();
		
		$invoice_code 	= 'InvoiceCode';
		$invoice_info 	= $api->getInvoiceID($invoice_code);

		//Build row actions
        $actions = array(
			'view'			=> sprintf('<a href="admin.php?page=wefact&action=%s&invoice=%s">'.__('View', 'wefact').'</a>','view',$item['InvoiceCode']),
//			'PDF'			=> sprintf('<a href="admin.php?page=wefact&action=%s&invoice=%s">'.__('PDF', 'wefact').'</a>','PDF',$item['InvoiceCode']),
//			'Email'			=> sprintf('<a href="admin.php?page=wefact&action=%s&invoice=%s">'.__('Email', 'wefact').'</a>','Email',$item['InvoiceCode']),
//			'Status'		=> sprintf('<a href="admin.php?page=wefact&action=%s&invoice=%s">'.__('Status', 'wefact').'</a>','Status',$item['InvoiceCode']),
//			'edit'			=> sprintf('<a href="?page=%s&action=%s&invoice=%s">'.__("Edit", "wefact").'</a>',$_REQUEST['page'],'edit',$item['Date']),
//          'delete'		=> sprintf('<a href="?page=%s&action=%s&invoice=%s">'.__("Delete", "wefact").'</a>',$_REQUEST['page'],'delete',$item['DebtorCode']),
        );
		
		return sprintf('%1$s %2$s', $item['InvoiceCode'], $this->row_actions($actions));
 	
    }
	
	
    /** ************************************************************************
     * REQUIRED if displaying checkboxes or using bulk actions! The 'cb' column
     * is given special treatment when columns are processed. It ALWAYS needs to
     * have it's own method.
     * 
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/
    function column_cb($item) 
	{
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("invoice")
            /*$2%s*/ $item['InvoiceCode']       //The value of the checkbox should be the record's InvoiceCode
        );
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
			'cb'			=> '<input type="checkbox" />',
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
	
    /** ************************************************************************
     * Optional. If you want one or more columns to be sortable (ASC/DESC toggle), 
     * you will need to register it here. This should return an array where the 
     * key is the column that needs to be sortable, and the value is db column to 
     * sort by. Often, the key and value will be the same, but this is not always
     * the case (as the value is a column name from the database, not the list table).
     * 
     * This method merely defines which columns should be sortable and makes them
     * clickable - it does not handle the actual sorting. You still need to detect
     * the ORDERBY and ORDER querystring variables within prepare_items() and sort
     * your data accordingly (usually by modifying your query).
     * 
     * @return array An associative array containing all the columns that should be sortable: 'slugs'=>array('data_values',bool)
     **************************************************************************/
    function get_sortable_columns() 
	{
        $sortable_columns = array(
            'Date'			=> array('Date', false),
			'InvoiceCode'	=> array('InvoiceCode', false),
			'CompanyName'	=> array('CompanyName', false),
			'Contact'		=> array('SurName', false)
        );
        return $sortable_columns;
    }
    
    
    /** ************************************************************************
     * Optional. If you need to include bulk actions in your list table, this is
     * the place to define them. Bulk actions are an associative array in the format
     * 'slug'=>'Visible Title'
     * 
     * If this method returns an empty value, no bulk action will be rendered. If
     * you specify any bulk actions, the bulk actions box will be rendered with
     * the table automatically on display().
     * 
     * Also note that list tables are not automatically wrapped in <form> elements,
     * so you will need to create those manually in order for bulk actions to function.
     * 
     * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_bulk_actions() 
	{
       $actions = array(
//			'status'	=> __('Status', 'wefact'),
//			'delete'    => __('Delete', 'wefact')
        );
        return $actions;
    }
    
    
    /** ************************************************************************
     * Optional. You can handle your bulk actions anywhere or anyhow you prefer.
     * For this example package, we will handle it in the class to keep things
     * clean and organized.
     * 
     * @see $this->prepare_items()
     **************************************************************************/
    function process_bulk_action() 
	{
		//Detect when a bulk action is being triggered...
//        if('delete'===$this->current_action() ) 
//        {
//            wp_die(__('Items deleted (or they would be if we had items to delete)!', 'wefact'));
//        }	
    }
	
	function no_items() 
	{
		_e( 'If you are expecting to see items and there are none, please check your site url and the security key.', 'wefact');
	}
    
    /** ************************************************************************
     * REQUIRED! This is where you prepare your data for display. This method will
     * usually be used to query the database, sort and filter the data, and generally
     * get it ready to be displayed. At a minimum, we should set $this->items and
     * $this->set_pagination_args(), although the following properties and methods
     * are frequently interacted with here...
     * 
     * @uses $this->_column_headers 
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
	 **************************************************************************/
    	
	public function prepare_items() 
	{
//		$per_page = get_option('wefact_per_page', 20);
        $per_page = 20;
        $columns = $this->get_columns();

        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        
        $this->_column_headers = array($columns, $hidden, $sortable);
		
        
        $this->process_bulk_action();
        
        
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
					"Order" 	=> "ASC",
					"Search" 	=> "",
        );
		
		$obj = $api->listInvoices($parameters)->Result->Invoices;
		
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
        $total_items = count($data);
                
        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently 
         * looking at. We'll need this later, so you should always include it in 
         * your own package classes.
         */
        $current_page = $this->get_pagenum();
        
        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to 
         */
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        
        /**
         * REQUIRED. Now we can add our *sorted* data to the items property, where 
         * it can be used by the rest of the class.
         */
         $this->items = $data;
	
        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args(array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
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
function wefact_render_invoice_list_page() {
    
    //Create an instance of our package class...
    $testListTable = new WeFact_Invoices_List_Table();
    //Fetch, prepare, sort, and filter our data...
    $testListTable->prepare_items();
    
    ?>
    <div class="wrap">
        
        <div id="icon-users" class="icon32"><br/></div>
        <h2><?php echo _e('WeFact', 'wefact'); ?> | <?php echo _e('Invoices list', 'wefact'); ?></h2>
        
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

				
