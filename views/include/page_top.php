<div class="wrap wefact">
	<div class="header">
		<ul class="nav">
			<?php foreach ($this->tabs as $route => $label): ?>
				<?php $class = ($route == $this->urlparts[0] ? 'active' : ''); ?>
				<li class="<?= $class ?>"><a href="admin.php?page=wefact&amp;route=<?= $route ?>"><?= $label ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<!-- Content -->