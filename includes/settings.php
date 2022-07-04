<?php

function sf_settings_page()
{
	global $sf_options;
	
	$styles = array('default', 'light', 'gray', 'dark', 'black', 'blue', 'red', 'green', 'yellow');
	$orders = array('title', 'post_date');
	$order_by = array('ASC', 'DESC');
	
	?>
	<div class="wrap">
		<div id="sf-wrap" class="sf-help">
			<h2>Sugar FAQs Settings</h2>
			<?php
			if ( ! isset( $_REQUEST['updated'] ) )
				$_REQUEST['updated'] = false;
			?>
			<?php if ( false !== $_REQUEST['updated'] ) : ?>
			<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
			<?php endif; ?>
			<form method="post" action="options.php">

				<?php settings_fields( 'sf_settings_group' ); ?>
				
				<h4>Style</h4>
				<p>
                    <select name="sf_settings[style]" style="width: 100px;">
						<?php foreach ($styles as $option) { ?>
							<option <?php if ($sf_options['style'] == $option ){ echo 'selected="selected"'; } ?>><?php echo htmlentities($option); ?></option>
						<?php } ?>
					</select>					
					<label class="description" for="sf_settings[style]"><?php _e( 'Choose your FAQs color scheme' ); ?></label>
				</p>
				
				<p>
					<input id="sf_settings[width]" name="sf_settings[width]" type="text" value="<?php echo $sf_options['width'];?>" />
					<span>px</span>
					<label class="description" for="sf_settings[width]"><?php _e( 'Enter a custom width for your FAQs. This is a global width used for all FAQs, unless specified in a shortcode parameter' ); ?></label><br/>
					
				</p>
				<p>
					<input id="sf_settings[icon]" name="sf_settings[icon]" type="checkbox" value="1" <?php checked( '1', $sf_options['icon'] ); ?>/>
					<label class="description" for="sf_settings[icon]"><?php _e( 'Disable icons? This will turn off the ? icon displayed to the left of the FAQ title' ); ?></label>
				</p>
				<p>
					<input id="sf_settings[single_open]" name="sf_settings[single_open]" type="checkbox" value="1" <?php checked( '1', $sf_options['single_open'] ); ?>/>
					<label class="description" for="sf_settings[single_open]"><?php _e( 'Enable "Single Open" mode, meaning that any open FAQ will be closed when another is clicked' ); ?></label>
				</p>
				
				<h4>Notifcations</h4>
				<p>
					<input id="sf_settings[email_notifications]" name="sf_settings[email_notifications]" type="checkbox" value="1" <?php checked( '1', $sf_options['email_notifications'] ); ?>/>
					<label class="description" for="sf_settings[single_open]"><?php _e( 'Send email notifications to the admin email when new questions are submitted? The question submission form can be displayed with the [faqs_form] short code' ); ?></label>
				</p>
				
				<h4>Display Order</h4>
				<p>
                    <select name="sf_settings[order]" style="width: 100px;">
						<?php foreach ($orders as $option) { ?>
							<option <?php if ($sf_options['order'] == $option ){ echo 'selected="selected"'; } ?>><?php echo htmlentities($option); ?></option>
						<?php } ?>
					</select>					
					<label class="description" for="sf_settings[order]"><?php _e( 'How to sort the FAQ entries' ); ?></label>
				</p>
				<p>
                    <select name="sf_settings[direction]" style="width: 100px;">
						<?php foreach ($order_by as $option) { ?>
							<option <?php if ($sf_options['direction'] == $option ){ echo 'selected="selected"'; } ?>><?php echo htmlentities($option); ?></option>
						<?php } ?>
					</select>					
					<label class="description" for="sf_settings[direction]"><?php _e( 'Display FAQs in Ascending or Descending Order' ); ?></label>
				</p>
				
				<h4>Custom CSS</h4>
				<p>
					<label class="description" for="sf_settings[css]"><?php _e( 'Enter your custom CSS here to customize the look of the FAQs. Click the "Help" tab in the upper right for a list of class names' ); ?></label><br/>
					<textarea id="sf_settings[width]" name="sf_settings[css]" rows="10" cols="80"><?php echo $sf_options['css'];?></textarea>
				</p>
				
				<!-- save the options -->
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Save Options' ); ?>" />
				</p>
								
				
			</form>
		</div><!--end sf-wrap-->
	</div><!--end wrap-->
		
	<?php
}

// register the plugin settings
function sf_register_settings() {

	// create whitelist of options
	register_setting( 'sf_settings_group', 'sf_settings' );
}
//call register settings function
add_action( 'admin_init', 'sf_register_settings' );

function sf_settings_menu() {
	// add settings page
	add_submenu_page('options-general.php', 'FAQs Settings', 'FAQs Settings','manage_options', 'sugar-faqs-settings', 'sf_settings_page');
}
add_action('admin_menu', 'sf_settings_menu');
