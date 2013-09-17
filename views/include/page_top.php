<?php
$tabs = array(
	'dashboard'     => __('Dashboard', 'wp_wefact'),
	'debtors'       => __('Debtors', 'wp_wefact'),
	'invoices'      => __('Invoices', 'wp_wefact'),
	'pricequotes'   => __('Pricequotes', 'wp_wefact'),
	'products'      => __('Products', 'wp_wefact'),
	'settings'      => __('Settings', 'wp_wefact')
);
?>
<div class="wrap wefact">
	<div class="header">
		<ul class="nav">
			<?php foreach ($tabs as $route => $label): ?>
				<?php $class = ($route == $this->urlparts[0] ? 'active' : ''); ?>
				<li class="<?= $class ?>"><a href="admin.php?page=wefact&amp;route=<?= $route ?>"><?= $label ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<!-- Content -->