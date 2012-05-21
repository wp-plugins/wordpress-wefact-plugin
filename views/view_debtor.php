<div class="wrap">
	<div id="icon-users" class="icon32"><br/></div>
	<h2><?php echo _e('WeFact', 'wefact'); ?> | <?php echo _e('Debtor', 'wefact'); ?></h2>
        
        <div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
        </div>
		<table width="80%">
			<thead>
				<tr>
					<th width="25%"><?php _e('Debtor information', 'wefact'); ?></th>
					<th width="25%"></th>
					<th width="25%"><?php _e('Debtor information', 'wefact'); ?></th>
					<th width="25%"></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><label for="debtor_debtor_code"><?php _e('Debtor code', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->DebtorCode); ?></td>
					<td><label for="debtor_company_name"><?php _e('Company name', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->CompanyName); ?></td>
				</tr>
				<tr>
					<td><label for="debtor_contact"><?php _e('Contact', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->Initials); ?> <?php print_r($debtor_info->Result->Debtor->SurName); ?></td>
					<td><label for="debtor_invoice_contact"><?php _e('Contact', 'wefact'); ?>:</label></td>
					<td><<?php print_r($debtor_info->Result->Debtor->InvoiceInitials); ?> <?php print_r($debtor_info->Result->Debtor->InvoiceSurName); ?></td>
				</tr>
				<tr>
					<td><label for="debtor_address"><?php _e('Address', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->Address); ?></td>
					<td><label for="debtor_invoice_method"><?php _e('Invoice method', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->InvoiceMethod); ?></td>
				</tr>
				<tr>
					<td><label for="debtor_zip_code"><?php _e('Zip code', 'wefact'); ?> / <?php _e('City', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->ZipCode); ?> <?php print_r($debtor_info->Result->Debtor->City); ?></td>
					<td><label for="debtor_invoice_address"><?php _e('Address', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->InvoiceAddress); ?></td>
				</tr>
				<tr>
					<td><label for="debtor_country_long"><?php _e('Country', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->CountryLong); ?></td>
					<td><label for="debtor_invoice_zip_code"><?php _e('Zip code', 'wefact'); ?> / <?php _e('City', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->InvoiceZipCode); ?> <?php print_r($debtor_info->Result->Debtor->InvoiceCity); ?></td>
				</tr>
				<tr>
					<td><label for="debtor_email_address"><?php _e('Email address', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->EmailAddress); ?></td>
					<td><label for="debtor_invoice_country_long"><?php _e('Country', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->InvoiceCountryLong); ?></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td><label for="debtor_invoice_email_address"><?php _e('Email address', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->InvoiceEmailAddress); ?></td>
				</tr>
				<tr>
					<td><label for="debtor_company_name"><?php _e('Company name', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->CompanyName); ?></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td><label for="debtor_legal_form"><?php _e('Legal form', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->LegalForm); ?></td>
					<td><label for="debtor_invoice_authorisation"><?php _e('Invoice authorisation', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->InvoiceAuthorisation); ?></td>
				</tr>
				<tr>
					<td><label for="debtor_company_number"><?php _e('Company number', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->CompanyNumber); ?></td>
					<td><label for="debtor_account_number"><?php _e('Account number', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->AccountNumber); ?></td>
				</tr>
				<tr>
					<td><label for="debtor_tax_number"><?php _e('Tax number', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->TaxNumber); ?></td>
					<td><label for="debtor_account_name"><?php _e('Account name', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->AccountName); ?></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td><label for="debtor_account_bank"><?php _e('Account bank', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->AccountBank); ?></td>
				</tr>
				<tr>
					<td><label for="debtor_phone_number"><?php _e('Phone number', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->PhoneNumber); ?></td>
					<td><label for="debtor_account_iban"><?php _e('Account Iban', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->AccountIban); ?></td>
				</tr>
				<tr>
					<td><label for="debtor_fax_number"><?php _e('Fax number', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->FaxNumber); ?></td>
					<td><label for="debtor_account_swift"><?php _e('Account swift', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->AccountSwift); ?></td>
				</tr>
				<tr>
					<td><label for="debtor_second_email_address"><?php _e('Second Email address', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->SecondEmailAddress); ?></td>
					<td><label for="debtor_account_city"><?php _e('Account city', 'wefact'); ?>:</label></td>
					<td><?php print_r($debtor_info->Result->Debtor->AccountCity); ?></td>
				</tr>
			</tbody>
		</table>	
	<p class="submit" style="margin-bottom: 60px;">
		<a class="button-primary" href="admin.php?page=wefact/debtors" title="<?php _e('Back to overview', 'wefact'); ?>"><?php _e('Back to overview', 'wefact'); ?></a>
	</p>
</div>
 
