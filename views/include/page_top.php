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
	<h2 class="nav-tab-wrapper">
		<?php foreach ($tabs as $route => $label): ?>
			<?php $class = ($route == $this->urlparts[0] ? 'nav-tab-active' : ''); ?>
				<a class="nav-tab <?php echo $class; ?>" href="admin.php?page=wefact&amp;route=<?php echo $route; ?>"><?php echo $label; ?></a>
		<?php endforeach; ?>
	</h2>
	<!-- Content -->