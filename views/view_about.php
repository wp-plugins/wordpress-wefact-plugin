<div class="wrap">
	<div id="icon-users" class="icon32"><br/></div>
	<h2><?php echo _e('WeFact', 'wefact'); ?> | <?php echo _e('About', 'wefact'); ?></h2>
        
        <div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
        </div>

<?php $lang=get_bloginfo("language");
	if($lang === 'nl-NL'):
?>
	<a href="http://www.tussendoor.nl/wordpress-plugins/wefact-wordpress-plugin/"><img src="<?php echo plugins_url('/wordpress-wefact-plugin/banners/meer_wp_plugins_nl.jpg');?>" alt="<?php echo _e('more wordpress plugins', 'wefact'); ?>"/></a>
	<a href="http://www.tussendoor.nl/wordpress-plugins/wefact-wordpress-plugin/"><img src="<?php echo plugins_url('/wordpress-wefact-plugin/banners/vragen_support_nl.jpg');?>"  alt="<?php echo _e('for questions', 'wefact'); ?>"/></a>
	
	<div id="about_text" style="margin-top:10px;width:80%;">	
		<?php _e('Dutch text', 'wefact');?>
	</div>

<?php
	elseif ($lang === 'en-EN'):
?>	
	<a href="http://www.tussendoor.nl/wordpress-plugins/wefact-wordpress-plugin/"><img src="<?php echo plugins_url('/wordpress-wefact-plugin/banners/meer_wp_plugins_en.jpg');?>" alt="<?php echo _e('more wordpress plugins', 'wefact'); ?>"/></a>
	<a href="http://www.tussendoor.nl/wordpress-plugins/wefact-wordpress-plugin/"><img src="<?php echo plugins_url('/wordpress-wefact-plugin/banners/vragen_support_en.jpg');?>"  alt="<?php echo _e('for questions', 'wefact'); ?>"/></a>
	
	<div id="about_text" style="margin-top:10px;width:80%;">	
		<?php _e('English text', 'wefact');?>
	</div>

<?php else:
?>
<?php endif;?>	
</div>