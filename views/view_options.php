<div class="wrap">
        
        <div id="icon-users" class="icon32"><br/></div>
        <h2><?php echo _e('WeFact', 'wefact'); ?> | <?php echo _e('Options', 'wefact'); ?></h2>
        <div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
        </div>
		
		<form name="view_options" id="settings" action="admin.php?page=wefact/settings" method="post">
		<table class="form-table">
			<tr>
				<th scope="row" valign="top"><label for="wefact_dashboard_toggle"><?php _e('Dashboard Widget','wefact'); ?></label></th>
				<td><input type="checkbox" name="wefact_dashboard_toggle" value="0" id="wefact_dashboard_toggle" <?php checked('1', get_option('wefact_dashboard_toggle')); ?> /><label for="wefact_dashboard_toggle"> <?php _e('Enabled','wefact'); ?></label><br />
	            <?php _e('Check this to display wefact dashboard widget.','wefact'); ?></td>
			</tr>
			<tr>
				<th scope="row" valign="top"><label for="ip_address"><?php _e('ip_address','wefact'); ?></label></th>
				<td><?php 
					$host = php_uname('n'); 
					if(!empty($host))
					{
						echo gethostbyname($host); 
					}
					else
					{ 
						_e('No IP address found.', 'wefact');
					}
					?>
				</td>
			</tr>
			<tr>
				<th scope="row" valign="top"><label for="clientURL"><?php _e('Enter your website url for wefact.','wefact'); ?></label></th>
				<td><input name="clientURL" id="clientURL" type="text" style="width: 350px;" value="<?php echo get_option('clientURL'); ?>"/></td>
			</tr>
			<tr>
				<th scope="row" valign="top"><label for="clientSecuritykey"><?php _e('Enter the given securitykey','wefact'); ?></label></th>
				<td><input name="clientSecuritykey" id="securitykey" type="text" style="width: 350px;" value="<?php echo get_option('clientSecuritykey'); ?>"/></td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" value="<?php _e('Save', 'wefact'); ?>" class="button-primary" />
		</p>
	</form>
</div>