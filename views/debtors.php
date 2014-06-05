<div class="wrap wefact">
	<?php echo WPWF::get_admin_tabs(); ?>
	<div class="section">
		<div class="grid-12">
			<h2><?php _e('Debtor overview', 'wp_wefact') ?></h2>
		</div>
	</div>

	<div class="section">
		<div class="grid-12">
			<table class="widefat fixed" cellspacing="0">
				<thead>
					<tr>
						<th><?php _e('Debtornr.', 'wp_wefact') ?></th>
						<th><?php _e('Name', 'wp_wefact') ?></th>
						<th><?php _e('E-mail address', 'wp_wefact') ?></th>
						<th><?php _e('Phonenumber', 'wp_wefact') ?></th>
						<th><?php _e('Place', 'wp_wefact') ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th><?php _e('Debtornr.', 'wp_wefact') ?></th>
						<th><?php _e('Name', 'wp_wefact') ?></th>
						<th><?php _e('E-mail address', 'wp_wefact') ?></th>
						<th><?php _e('Phonenumber', 'wp_wefact') ?></th>
						<th><?php _e('Place', 'wp_wefact') ?></th>
					</tr>
				</tfoot>
				<tbody>
					<?php $i = 0; ?>
					<?php foreach ( $debtors as $row): ?>
						<tr <?php echo ($i&1 ? 'class="alternate"' : '') ?>>
							<td>
								<a href="<?php echo admin_url('admin.php?page=wpwf_view_debtor&id='.$row['Identifier']); ?>">
									<?php echo $row['DebtorCode'] ?>
								</a>
							</td>
							<td>
								<a href="<?php echo admin_url('admin.php?page=wpwf_view_debtor&id='.$row['Identifier']); ?>">
									<?php echo $row['CompanyName'] ?>
								</a>
							</td>
							<td><a href="mailto:<?php echo $row['EmailAddress'] ?>"><?php echo $row['EmailAddress'] ?></a></td>
							<td><?php echo $row['PhoneNumber'] ?></td>
							<td><?php echo $row['City'] ?></td>
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
<?php include(WPWF_PLUGIN_DIR . '/views/footer.php'); ?>