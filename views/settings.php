<div class="section">
	<div class="grid-12">
		<h2><?php _e('API settings WeFact', 'wp_wefact') ?></h2>
		<?php $this->showMsg() ?>
	</div>
	<div class="grid-12">
		<?php WPWF::open_form('admin.php?page=wefact&route=settings'); ?>
		<p>
			<?php WPWF::label('', __('Server IP address', 'wp_wefact') . ':'); ?>
			<strong><?php echo $_SERVER['REMOTE_ADDR'] ?></strong>
		</p>
		<p>
			<?php WPWF::label('wefact_type', __('WeFact version', 'wp_wefact') . ':'); ?>
			<?php WPWF::select('wefact_type', array('standard' => 'WeFact Standard', 'hosting' => 'WeFact Hosting'), get_option('wefact_type')) ?>
		</p>
	<?php $display = (get_option('wefact_type') == 'standard' ? 'none' : 'block') ?>
		<p id="wefact_api_url" style="display: <?php echo $display ?>">
			<?php WPWF::label('wefact_url', __('WeFact API URL', 'wp_wefact') . ':'); ?>
			<?php WPWF::textfield('wefact_url', get_option('wefact_url'), array('size' => 40)); ?>
		</p>
		<p>
			<?php WPWF::label('wefact_key', __('Security key', 'wp_wefact') . ':'); ?>
			<?php WPWF::textfield('wefact_key', get_option('wefact_key'), array('size' => 40)); ?>
		</p>
		<p>
			<?php WPWF::submit(__('Save', 'wp_wefact')); ?>
		</p>
		<?php WPWF::close_form(); ?>
	</div>
</div>

<div class="section">
	<div class="grid-6">
		<p><img src="<?php echo plugins_url('/wordpress-wefact-plugin/images/api-1.png') ?>"></p>
		<p>
			<?php _e('For more information, see:', 'wp_wefact') ?>
			<a href="https://www.wefact.nl/wefact-hosting/api/beginnen-met-de-api?ref=Tussendoor">
				https://www.wefact.nl/wefact-hosting/api/beginnen-met-de-api/
			</a>
		</p>
	</div>
	<div class="grid-6">
		<p><img src="<?php echo plugins_url('/wordpress-wefact-plugin/images/api-2.png') ?>"></p>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(document).on('change', '#wefact_wefact_type', function() {
			var selected = $("option:selected", this).val();
			if (selected == 'hosting') {
				$('#wefact_api_url').show();
			}
			else {
				$('#wefact_api_url').hide();
			}
		});
	});
</script>