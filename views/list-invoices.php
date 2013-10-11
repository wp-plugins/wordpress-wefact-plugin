<div class="section">
	<div class="grid-12">
		<h2><?php _e('Invoices overview', 'wp_wefact') ?></h2>
		<?php $this->showMsg() ?>
	</div>
</div>

<div class="section">
	<div class="grid-12">
		<table class="table-horizontal" cellspacing="0">
			<thead>
				<tr>
					<th><?php _e('Invoicenr.', 'wp_wefact') ?></th>
					<th><?php _e('Debtor', 'wp_wefact') ?></th>
					<th><?php _e('Price incl. VAT', 'wp_wefact') ?></th>
					<th><?php _e('Invoice date', 'wp_wefact') ?></th>
					<th><?php _e('Status', 'wp_wefact') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php $i = 0; ?>
				<?php foreach ( (array) $invoices as $row): ?>
					<tr <?= ($i&1 ? 'class="alternate"' : '') ?>>
						<td>
							<a href="admin.php?page=wefact&amp;route=invoices/view/<?= $row->Identifier ?>">
								<?= $row->InvoiceCode ?>
							</a>
						</td>
						<td>
							<a href="admin.php?page=wefact&amp;route=debtors/view/<?= $row->Debtor ?>">
								<?= $row->CompanyName ?>
							</a>
						</td>
						<td><?= WPWF::price($row->AmountIncl) ?></td>
						<td><?= WPWF::dmy($row->Date) ?></td>
						<td>
							<?php echo WPWF::invoice_statuses($row->Status) ?>
							<?php if ($row->Status == 2): ?>
								(<a href="admin.php?page=wefact&amp;route=invoices/paid/<?= $row->Identifier ?>"><?php _e('Paid', 'wp_wefact') ?></a>)
							<?php endif; ?>
						</td>
					</tr>
				<?php $i++; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
		if ($pages > 1) {
			for($i = 1; $i <= $pages; $i++) {
				$class = ($i == $curpage ? 'active' : '');
				echo '<a href="admin.php?page=wefact&route=invoices&p=' . $i . '" class="pagination '.$class.'">' . $i . '</a>';
			}
		}
		?>
	</div>
</div>