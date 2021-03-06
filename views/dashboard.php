<div class="wrap wefact">
	<?php echo WPWF::get_admin_tabs(); ?>
	<div class="section">
		<div class="grid-8">
			<h2><?php _e('Open invoices', 'wp_wefact') ?></h2>
			<?php $this->showMsg() ?>
		</div>
		<div class="grid-4">
			<h2><?php _e('Statistics', 'wp_wefact') ?></h2>
		</div>
	</div>

	<div class="section">
		<div class="grid-8">	
			<table class="widefat fixed" cellspacing="0">
				<thead>
					<tr>
						<th><?php _e('Invoicenr.', 'wp_wefact') ?></th>
						<th><?php _e('Debtor', 'wp_wefact') ?></th>
						<th><?php _e('Price incl. VAT', 'wp_wefact') ?></th>
						<th><?php _e('Status', 'wp_wefact') ?></th>
					</tr>
				</thead>
				 <tfoot>
				    <tr>
						<th><?php _e('Invoicenr.', 'wp_wefact') ?></th>
						<th><?php _e('Debtor', 'wp_wefact') ?></th>
						<th><?php _e('Price incl. VAT', 'wp_wefact') ?></th>
						<th><?php _e('Status', 'wp_wefact') ?></th>
					</tr>
				</tfoot>
				<tbody>
					<?php $i = 0; ?>
					<?php foreach ( (array) $invoices as $row): ?>
						<tr <?php echo ($i&1 ? 'class="alternate"' : ''); ?>>
							<td>
								<a href="<?php echo admin_url('admin.php?page=wpwf_view_invoice&id='.$row['Identifier']); ?>">
									<?php echo $row['InvoiceCode']; ?>
								</a>
							</td>
							<td>
								<a href="<?php echo admin_url('admin.php?page=wpwf_view_debtor&id='.$row['Debtor']); ?>">
									<?php echo $row['CompanyName']; ?>
								</a>
							</td>
							<td><?php echo WPWF::price($row['AmountIncl']); ?></td>
							<td>
								<?php _e('Waiting for payment', 'wp_wefact') ?>
								(<a href="<?php echo admin_url('admin.php?page=wpwf_view_invoice&id='.$row['Identifier'].'&status=4'); ?>"><?php _e('Paid', 'wp_wefact') ?></a>)
							</td>
						</tr>
					<?php $i++; ?>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<div class="grid-4">
			<h3><?php _e('Revenue this year', 'wp_wefact') ?></h3>
			<table>
				<tr>
					<td class="total_excl"><?php echo WPWF::price($total['revenue']) ?></td>
					<td class="small"><?php _e('Excl. VAT', 'wp_wefact') ?></td>
				</tr>
				<tr>
					<td class="total_incl grey"><?php echo WPWF::price($total['revenue'] * 1.21) ?></td>
					<td class="small grey"><?php _e('Incl. VAT', 'wp_wefact') ?></td>
				</tr>
			</table>
		</div>
	</div>
<?php include(WPWF_PLUGIN_DIR . '/views/footer.php'); ?>