<div class="section">
	<div class="grid-12">
		<h2>Producten overzicht</h2>
	</div>
</div>

<div class="section">
	<div class="grid-12">
		<table class="table-horizontal" cellspacing="0">
			<thead>
				<tr>
					<th><?php _e('Productnr.', 'wp_wefact') ?></th>
					<th><?php _e('Name', 'wp_wefact') ?></th>
					<th><?php _e('Product type', 'wp_wefact') ?></th>
					<th><?php _e('Price excl. VAT', 'wp_wefact') ?></th>
					<th><?php _e('Amount sold', 'wp_wefact') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php $i = 0; ?>
				<?php foreach ( (array) $products as $p): ?>
					<tr <?= ($i&1 ? 'class="alternate"' : '') ?>>
						<td><?= $p->ProductCode ?></td>
						<td><?= $p->ProductName ?></td>
						<td>
							<?php switch ($p->ProductType) {
								case 'domain':
								_e('Domain', 'wp_wefact');
								break;
								case 'other':
								_e('Other', 'wp_wefact');
								break;
							} ?>
						</td>
						<td><?= price($p->PriceExcl) ?></td>
						<td><?= $p->Sold ?> x</td>
					</tr>
				<?php $i++; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
		if ($pages > 1) {
			for ($i = 1; $i <= $pages; $i++) {
				$class = ($i == $curpage ? 'active' : '');
				echo '<a href="admin.php?page=wefact&route=products&p=' . $i . '" class="pagination '.$class.'">' . $i . '</a>';
			}
		}
		?>
	</div>
</div>