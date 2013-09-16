<div class="section">
	<div class="grid-12">
		<h2><?php _e('Debtor', 'wp_wefact') ?>: <?= $debtor->CompanyName ?></h2>
		<?php $this->showMsg() ?>
	</div>
</div>

<div class="section">
	<div class="grid-6 details">
		<h3><?php _e('Debtor information', 'wp_wefact') ?></h3>
		<table>
			<tbody>
				<tr>
					<th><?php _e('Company name', 'wp_wefact') ?>:</th>
					<td><?php echo $debtor->CompanyName ?></td>
				</tr>
				<tr>
					<th><?php _e('Company number', 'wp_wefact') ?>:</th>
					<td><?php echo $debtor->CompanyNumber ?></td>
				</tr>
				<tr>
					<th><?php _e('VAT-number', 'wp_wefact') ?>:</th>
					<td><?php echo $debtor->TaxNumber ?></td>
				</tr>
				<tr>
					<th><?php _e('Legalform', 'wp_wefact') ?>:</th>
					<td><?php echo $debtor->LegalForm ?></td>
				</tr>
				<tr>
					<th><?php _e('Contact person', 'wp_wefact') ?>:</th>
					<td>
						<?php echo $debtor->Initials ?>
						<?php echo $debtor->SurName ?>
					</td>
				</tr>
				<tr>
					<th><?php _e('Address', 'wp_wefact') ?>:</th>
					<td>
						<?php echo $debtor->Address ?><br>
						<?php echo $debtor->ZipCode ?> <?php echo $debtor->City ?><br>
						<?php echo $debtor->CountryLong ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="grid-6 align-right">
		<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.nl/maps?q=<?php echo $debtor->Address.', '.$debtor->City ?>&amp;t=m&amp;z=12&amp;f=q&amp;output=embed"></iframe>
	</div>
</div>

<div class="section">
	<div class="grid-12">
		<h3><?php _e('Subscriptions', 'wp_wefact') ?></h3>
		<?php if (! empty($subscriptions)): ?>
			<table class="wefact table-horizontal fixed" cellspacing="0">
				<thead>
					<tr>
						<th><?php _e('Description', 'wp_wefact') ?></th>
						<th><?php _e('Price excl. VAT', 'wp_wefact') ?></th>
						<th><?php _e('Invoicing on', 'wp_wefact') ?></th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 0; ?>
					<?php foreach ( (array) $subscriptions as $s): ?>
						<tr <?= ($i&1 ? 'class="alternate"' : '') ?>>
							<td><?php echo $s->Description ?></td>
							<td><?php echo price($s->PriceExcl) ?></td>
							<td><?php echo $s->NextDate ?></td>
						</tr>
					<?php $i++; ?>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else: ?>
			<?php _e('No subscriptions found', 'wp_wefact') ?>
		<?php endif; ?>
	</div>
</div>

<div class="section">
	<div class="grid-12">
		<h3><?php _e('Invoices', 'wp_wefact') ?></h3>
		<?php if (! empty($invoices)): ?>
			<table class="wefact table-horizontal fixed" cellspacing="0">
				<thead>
					<tr>
						<th><?php _e('Debtornr.', 'wp_wefact') ?></th>
						<th><?php _e('Price incl. VAT', 'wp_wefact') ?></th>
						<th><?php _e('Status', 'wp_wefact') ?></th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 0; ?>
					<?php foreach ( (array) $invoices as $row): ?>
						<tr <?= ($i&1 ? 'class="alternate"' : '') ?>>
							<td><a href="admin.php?page=wefact&amp;route=invoices/view/<?php echo $row->Identifier ?>"><?= $row->InvoiceCode ?></a></td>
							<td><?php echo price($row->AmountIncl) ?></td>
							<td>
								<?php echo invoice_statuses($row->Status) ?>
								<?php if ($row->Status == 2): ?>
									(<a href="admin.php?page=wefact&amp;route=invoices/paid/<?= $row->Identifier ?>"><?php _e('Paid', 'wp_wefact') ?></a>)
								<?php endif; ?>
							</td>
						</tr>
					<?php $i++; ?>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else: ?>
			<?php _e('No invoices found', 'wp_wefact') ?>
		<?php endif; ?>
	</div>
</div>

<div class="section">
	<div class="grid-12">
		<h3><?php _e('Pricequotes', 'wp_wefact') ?></h3>
		<?php if (! empty($pricequotes)): ?>
			<table class="wefact table-horizontal fixed" cellspacing="0">
				<thead>
					<tr>
						<th><?php _e('Pricequotenr.', 'wp_wefact') ?></th>
						<th><?php _e('Price incl. VAT', 'wp_wefact') ?></th>
						<th><?php _e('Status', 'wp_wefact') ?></th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 0; ?>
					<?php foreach ( (array) $pricequotes as $row): ?>
						<tr <?= ($i&1 ? 'class="alternate"' : '') ?>>
							<td><a href="admin.php?page=wefact&amp;route=pricequotes/view/<?php echo $row->Identifier ?>"><?= $row->PriceQuoteCode ?></a></td>
							<td><?php echo price($row->AmountIncl) ?></td>
							<td>
								<?php echo pricequote_statuses($row->Status) ?>
								<?php if ($row->Status == 2): ?>
									(<a href="admin.php?page=wefact&amp;route=pricequotes/accepted/<?= $row->Identifier ?>"><?php _e('Accepted', 'wp_wefact') ?></a> | <a href="admin.php?page=wefact&amp;route=pricequotes/declined/<?= $row->Identifier ?>"><?php _e('Declined', 'wp_wefact') ?></a>)
								<?php endif; ?>
							</td>
						</tr>
					<?php $i++; ?>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else: ?>
			<?php _e('No pricequotes found', 'wp_wefact') ?>
		<?php endif; ?>
	</div>
</div>