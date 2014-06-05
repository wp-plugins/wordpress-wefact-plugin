<div class="wrap wefact">
	<h2 class="nav-tab-wrapper">
		<a class="nav-tab" href="<?php echo admin_url('admin.php?page=wpwf_dashboard'); ?>">&lt;</a>
		<a class="nav-tab nav-tab-active" href="<?php echo admin_url('admin.php?page=wpwf_view_pricequote'); ?>">Offerte</a>
	</h2>
	<div class="section">
		<div class="grid-12">
			<h2><?php _e('Pricequote', 'wp_wefact') ?>: <?php echo $pricequote['PriceQuoteCode']; ?></h2>
			<?php $this->showMsg(); ?>
		</div>
	</div>

	<div class="section">
		<div class="grid-4 details">
			<p>
				<a href="admin.php?page=wefact&amp;route=debtors/view/<?php echo $pricequote['Debtor'] ?>">
					<?php echo $pricequote['CompanyName'] ?>
				</a>
			</p>
			<p><?php _e('attn.', 'wp_wefact') ?> <?php echo $pricequote['Initials'] ?> <?php echo $pricequote['SurName'] ?></p>
			<p><?php echo $pricequote['Address'] ?></p>
			<p><?php echo $pricequote['ZipCode'] ?> <?php echo $pricequote['City'] ?></p>
		</div>
		<div class="grid-8 details">
			<table>
				<tr>
					<th><?php _e('Debtornr.', 'wp_wefact') ?>:</th>
					<td><?php echo $debtor['DebtorCode'] ?></td>
				</tr>
				<tr>
					<th><?php _e('pricequotenr.', 'wp_wefact') ?>:</th>
					<td><?php echo $pricequote['PriceQuoteCode'] ?></td>
				</tr>
				<tr>
					<th><?php _e('Date', 'wp_wefact') ?>:</th>
					<td><?php echo WPWF::dmy($pricequote['Date']) ?></td>
				</tr>
			</table>
		</div>
	</div>

	<div class="section">
		<div class="grid-12">
			<table class="widefat fixed" cellspacing="0">
				<thead>
					<tr>
						<th><?php _e('Date', 'wp_wefact') ?></th>
						<th><?php _e('Quantity', 'wp_wefact') ?></th>
						<th><?php _e('Product', 'wp_wefact') ?></th>
						<th><?php _e('Description', 'wp_wefact') ?></th>
						<th><?php _e('VAT', 'wp_wefact') ?></th>
						<th><?php _e('Price', 'wp_wefact') ?></th>
						<th><?php _e('Total incl. VAT', 'wp_wefact') ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th><?php _e('Date', 'wp_wefact') ?></th>
						<th><?php _e('Quantity', 'wp_wefact') ?></th>
						<th><?php _e('Product', 'wp_wefact') ?></th>
						<th><?php _e('Description', 'wp_wefact') ?></th>
						<th><?php _e('VAT', 'wp_wefact') ?></th>
						<th><?php _e('Price', 'wp_wefact') ?></th>
						<th><?php _e('Total incl. VAT', 'wp_wefact') ?></th>
					</tr>
				</tfoot>
				<tbody>
					<?php $i = 0; ?>
					<?php foreach ( (array) $pricequote['PriceQuoteLines'] as $row): ?>
						<tr <?php echo ($i&1 ? 'class="alternate"' : ''); ?>>
							<td><?php echo $row['Date']; ?></td>
							<td><?php echo $row['Number']; ?> x</td>
							<td><?php echo $row['ProductCode']; ?></td>
							<td><?php echo $row['Description']; ?></td>
							<td><?php echo WPWF::percentage($row['TaxPercentage']) ?></td>
							<td><?php echo WPWF::price($row['PriceExcl']) ?></td>
							<td><?php echo WPWF::price($row['Number'] * $row['PriceExcl']) ?></td>
						</tr>
					<?php $i++; ?>
					<?php endforeach; ?>
					<tr class="bold">
						<td colspan="5"></td>
						<td><?php _e('Total excl. VAT', 'wp_wefact') ?></td>
						<td><?php echo WPWF::price($invoice['AmountExcl']) ?></td>
					</tr>
					<tr>
						<td colspan="5"></td>
						<td><?php _e('Total', 'wp_wefact') ?> <?php _e('VAT', 'wp_wefact') ?></td>
						<td><?php echo WPWF::price($invoice['AmountIncl'] - $invoice['AmountExcl']) ?></td>
					</tr>
					<tr>
						<td colspan="5"></td>
						<td><?php _e('Total excl. VAT', 'wp_wefact') ?></td>
						<td><?php echo WPWF::price($invoice['AmountIncl']) ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>