<div class="section">
	<div class="grid-12">
		<h2><?php _e('Debtor overview', 'wp_wefact') ?></h2>
	</div>
</div>

<div class="section">
	<div class="grid-12">
		<table class="table-horizontal" cellspacing="0">
			<thead>
				<tr>
					<th><?php _e('Debtornr.', 'wp_wefact') ?></th>
					<th><?php _e('Name', 'wp_wefact') ?></th>
					<th><?php _e('E-mail address', 'wp_wefact') ?></th>
					<th><?php _e('Phonenumber', 'wp_wefact') ?></th>
					<th><?php _e('Place', 'wp_wefact') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php $i = 0; ?>
				<?php foreach ( (array) $debtors as $row): ?>
					<tr <?= ($i&1 ? 'class="alternate"' : '') ?>>
						<td>
							<a href="admin.php?page=wefact&amp;route=debtors/view/<?= $row->Identifier ?>">
								<?= $row->DebtorCode ?>
							</a>
						</td>
						<td>
							<a href="admin.php?page=wefact&amp;route=debtors/view/<?= $row->Identifier ?>">
								<?= $row->CompanyName ?>
							</a>
						</td>
						<td><a href="mailto:<?= $row->EmailAddress ?>"><?= $row->EmailAddress ?></a></td>
						<td><?php echo $row->PhoneNumber ?></td>
						<td><?= $row->City ?></td>
					</tr>
				<?php $i++; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
		if ($pages > 1) {
			for($i = 1; $i <= $pages; $i++) {
				$class = ($i == $curpage ? 'active' : '');
				echo '<a href="admin.php?page=wefact&route=debtors&p=' . $i . '" class="pagination '.$class.'">' . $i . '</a>';
			}	
		}
		?>
	</div>
</div>