<div class="wrap wefact">
	<?php echo WPWF::get_admin_tabs(); ?>
	<div class="section">
		<div class="grid-12">
			<h2><?php _e('API settings WeFact', 'wp_wefact') ?></h2>
			<?php $this->showMsg() ?>
		</div>
		<div class="grid-12">
			<?php WPWF::open_form('admin.php?page=wpwf_settings'); ?>
			<p>
				<?php WPWF::label('', __('Server IP address', 'wp_wefact') . ':'); ?>
				<strong><?php echo WPWF::get_ip(); ?></strong>
			</p>
			<p>
				<?php WPWF::label('wpwf_api_type', __('WeFact version', 'wp_wefact') . ':'); ?>
				<?php WPWF::select('wpwf_api_type', array('standard' => 'WeFact Standard', 'hosting' => 'WeFact Hosting'), get_option('wpwf_api_type')) ?>
			</p>
			<p id="wpwf_api_version" style="display: <?php echo $display ?>;">
				<?php WPWF::label('wpwf_api_version', __('API version', 'wp_wefact') . ':'); ?>
				<?php WPWF::select('wpwf_api_version', array('1.07' => '1.07', '2.00' => '2.00'), get_option('wpwf_api_version')) ?>
			</p>
			<p id="wpwf_api_url" style="display: <?php echo $display ?>;">
				<?php WPWF::label('wpwf_api_url', __('WeFact API URL', 'wp_wefact') . ':'); ?>
				<?php WPWF::textfield('wpwf_api_url', get_option('wpwf_api_url'), array('size' => 40)); ?>
			</p>
			<p>
				<?php WPWF::label('wpwf_api_key', __('Security key', 'wp_wefact') . ':'); ?>
				<?php WPWF::textfield('wpwf_api_key', get_option('wpwf_api_key'), array('size' => 40)); ?>
			</p>
			<p>
				<?php WPWF::submit(__('Save', 'wp_wefact')); ?>
			</p>
			<?php WPWF::close_form(); ?>
		</div>
	</div>

	<div class="section">
		<div class="grid-6">
			<p><img src="<?php echo WPWF_PLUGIN_URL . '/assets/images/api-1.png'; ?>"></p>
			<p>
				<?php _e('For more information, see:', 'wp_wefact') ?>
				<a href="https://www.wefact.nl/wefact-hosting/api/beginnen-met-de-api?ref=Tussendoor">
					https://www.wefact.nl/wefact-hosting/api/beginnen-met-de-api/
				</a>
			</p>
		</div>
		<div class="grid-6">
			<p><img src="<?php echo WPWF_PLUGIN_URL.'/assets/images/api-2.png'; ?>"></p>
		</div>
	</div>
<?php include(WPWF_PLUGIN_DIR . '/views/footer.php'); ?>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(document).on('change', '#wefact_wpwf_api_type', function() {
			var selected = $("option:selected", this).val();
			if (selected == 'hosting') {
				$('#wpwf_api_url').show();
				$('#wpwf_api_version').show();
			}
			else {
				$('#wpwf_api_url').hide();
				$('#wpwf_api_version').hide();
			}
		});
	});
</script>