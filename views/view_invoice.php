<div class="wrap">
	<div id="icon-users" class="icon32"><br/></div>
	<h2><?php echo _e('WeFact', 'wefact'); ?> | <?php echo _e('Invoice', 'wefact'); ?></h2>
        
        <div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
        </div>
		<form method="get">
			<table width="80%">
				<thead>
					<tr>
						<th width="25%"><?php _e('Invoice information', 'wefact'); ?></th>
						<th width="25%"></th>
						<th width="25%"><?php _e('Invoice information', 'wefact'); ?></th>
						<th width="25%"></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><label for="invoice_debtor_code"><?php _e('Debtor code', 'wefact'); ?>:</label></td>
						<td><?php print_r($invoice_info->Result->Invoice->DebtorCode); ?></td>
						<td><label for="invoice_authorisation"><?php _e('Authorisation', 'wefact'); ?>:</label></td>
						<td><?php print_r($invoice_info->Result->Invoice->Authorisation); ?></td>
					</tr>
					<tr>
						<td><label for="invoice_invoice_code"><?php _e('Invoice code', 'wefact'); ?>:</label></td>
						<td><?php print_r($invoice_info->Result->Invoice->InvoiceCode); ?></td>
						<td><label for="invoice_invoice_method"><?php _e('Invoice method', 'wefact'); ?>:</label></td>
						<td><?php echo ($invoice_info->Result->Invoice->InvoiceMethod === 0) ? _e('By email', 'wefact') : '';
								  echo ($invoice_info->Result->Invoice->InvoiceMethod === 1) ? _e('By post', 'wefact') : '';
								  echo ($invoice_info->Result->Invoice->InvoiceMethod === 3) ? _e('By email by post', 'wefact') : ''; ?>
						</td>
					</tr>
					<tr>
						<td><label for="invoice_date"><?php _e('Date', 'wefact'); ?></label>:</td>
						<td><?php print_r($invoice_info->Result->Invoice->Date); ?></td>
						<td><label for="invoice_sent_date"><?php _e('Sent date', 'wefact'); ?>:</label></td>
						<td><?php ($invoice_info->Result->Invoice->SentDate === '0000-00-00 00:00:00') ? '' : print_r($invoice_info->Result->Invoice->SentDate); ?></td>
					</tr>
					<tr>
						<td><label for="invoice_description"><?php _e('Description', 'wefact'); ?>:</label></td>
						<td><?php print_r($invoice_info->Result->Invoice->Description); ?></td>
						<td><label for="invoice_sent"><?php _e('Sent', 'wefact'); ?>:</label></td>
						<td><?php print_r($invoice_info->Result->Invoice->Sent); ?>x</td>
					</tr>
					<tr>
						<td><label for="invoice_term"><?php _e('Term', 'wefact'); ?>:</label></td>
						<td><?php print_r($invoice_info->Result->Invoice->Term); ?> dagen</td>
						<td><label for="invoice_reminders"><?php _e('Reminders', 'wefact'); ?>:</label></td>
						<td><?php print_r($invoice_info->Result->Invoice->Reminders); ?>x</td>
					</tr>
					<tr>
						<td><label for="invoice_discount"><?php _e('Discount', 'wefact'); ?>:</label></td>
						<td><?php print_r($invoice_info->Result->Invoice->Discount); ?> %</td>
						<td><label for="invoice_reminder_date"><?php _e('Reminder date', 'wefact'); ?>:</label></td>
						<td><?php ($invoice_info->Result->Invoice->RemiderDate === '0000-00-00') ? '' : print_r($invoice_info->Result->Invoice->RemiderDate); ?></td>
					</tr>
					<tr>
						<td><label for="invoice_reference_number"><?php _e('Reference number', 'wefact'); ?>:</label></td>
						<td><?php print_r($invoice_info->Result->Invoice->ReferenceNumber); ?></td>
						<td><label for="invoice_summations"><?php _e('Summations', 'wefact'); ?>:</label></td>
						<td><?php print_r($invoice_info->Result->Invoice->Summations); ?>x</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td><label for="invoice_summation_date"><?php _e('Summation date', 'wefact'); ?>:</label></td>
						<td><?php ($invoice_info->Result->Invoice->SummationDate === '0000-00-00') ? '' : print_r($invoice_info->Result->Invoice->SummationDate); ?></td>
					</tr>
					<tr>
						<td><label for="invoice_company_name"><?php _e('Company name', 'wefact'); ?>:</label></td>
						<td><?php print_r($invoice_info->Result->Invoice->CompanyName); ?></td>
						<td><label for="invoice_payment_method"><?php _e('Payment method', 'wefact'); ?>:</label></td>
						<td><?php print_r($invoice_info->Result->Invoice->PaymentMethod); ?></td>
					</tr>
					<tr>
						<td><label for="invoice_initials"><?php _e('Contact', 'wefact'); ?>:</label></td>
						<td><?php print_r($invoice_info->Result->Invoice->Initials); ?> <?php print_r($invoice_info->Result->Invoice->SurName); ?></td>
						<td><label for="invoice_transaction_id"><?php _e('Transaction ID', 'wefact'); ?>:</label></td>
						<td><?php print_r($invoice_info->Result->Invoice->TransactionID); ?></td>
					</tr>
					<tr>
						<td><label for="invoice_address"><?php _e('Address', 'wefact'); ?>:</label></td>
						<td><?php print_r($invoice_info->Result->Invoice->Address); ?></td>
						<td><label for="invoice_status"><?php _e('Status', 'wefact'); ?>:</label></td>
						<td><?php echo ($invoice_info->Result->Invoice->Status === 0) ? _e('Draft invoice', 'wefact') : '';
								  echo ($invoice_info->Result->Invoice->Status === 2) ? _e('Sent', 'wefact') : '';
								  echo ($invoice_info->Result->Invoice->Status === 3) ? _e('Partly paid', 'wefact') : '';
								  echo ($invoice_info->Result->Invoice->Status === 4) ? _e('Paid', 'wefact') : '';
								  echo ($invoice_info->Result->Invoice->Status === 8) ? _e('Credit invoice', 'wefact') : '';
								  echo ($invoice_info->Result->Invoice->Status === 9) ? _e('Expired', 'wefact') : ''; ?>
						</td>
					</tr>
					<tr>
						<td><label for="invoice_zip_code"><?php _e('Zip code', 'wefact'); ?>:</label></td>
						<td><?php print_r($invoice_info->Result->Invoice->ZipCode); ?></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td><label for="invoice_city"><?php _e('City', 'wefact'); ?></label>:</td>
						<td><?php print_r($invoice_info->Result->Invoice->City); ?></td>
						<td><label for="invoice_amount_excl"><?php _e('Amount excl', 'wefact'); ?>:</label></td>
						<td>€ <?php print_r($invoice_info->Result->Invoice->AmountExcl); ?></td>
					</tr>
					<tr>
						<td><label for="invoice_country"><?php _e('Country', 'wefact'); ?>:</label></td>
						<td><?php print_r($invoice_info->Result->Invoice->Country); ?></td>
						<td><label for="invoice_amount_incl"><?php _e('Amount incl', 'wefact'); ?>:</label></td>
						<td>€ <?php print_r($invoice_info->Result->Invoice->AmountIncl); ?></td>
					</tr>
					<tr>
						<td><label for="invoice_email_address"><?php _e('Email address', 'wefact'); ?>:</label></td>
						<td><?php print_r($invoice_info->Result->Invoice->EmailAddress); ?></td>
						<td></td>
						<td></td>
					</tr>
				</tbody>
			</table>	 
		</form>
	<p class="submit">
		<a class="button-primary" href="admin.php?page=wefact" title="<?php _e('Back to overview', 'wefact'); ?>"><?php _e('Back to overview', 'wefact'); ?></a>
		<a class="button-secondary" href="admin.php?page=wefact&action=pdf&invoice=<?php echo $id; ?>" title="<?php _e('Download as PDF', 'wefact'); ?>"><?php _e('Download as PDF', 'wefact'); ?></a>
		<a class="button-secondary" href="admin.php?page=wefact&action=status&invoice=<?php echo $id; ?>" title="<?php _e('Change Status to paid', 'wefact'); ?>"><?php _e('Change status to paid', 'wefact'); ?></a>
	</p>
</div>
