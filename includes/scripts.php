<?php

// front end scripts
function sf_register_scripts()
{
	global $sfBaseDir, $sf_version;
	wp_register_script('sf-accordion', sf_make_protocol_relative_url( $sfBaseDir ) . 'includes/js/jquery.mfx-accordion.js', array( 'jquery' ), $sf_version, TRUE );
	// wp_register_style('sugar-faqs-css', $sfBaseDir . 'includes/css/sugar-faqs-css.css');
	//wp_enqueue_script('jquery');
}
add_action('init', 'sf_register_scripts');

function sf_enqueu_scripts()
{
	global $sfBaseDir, $sf_version;
	wp_enqueue_style('sugar-faqs-css', sf_make_protocol_relative_url( $sfBaseDir ) . 'includes/css/sugar-faqs-css.css', array(), $sf_version );
}
add_action('wp_enqueue_scripts', 'sf_enqueu_scripts');

function sf_print_scripts() {
	global $sf_load_scripts, $sf_options;

	if(!$sf_load_scripts)
		return;

	wp_print_scripts('sf-accordion');
	// wp_print_styles('sugar-faqs-css');
	if(isset($sf_options['single_open'])) { $singleOpen = 'true'; } else { $singleOpen = 'false'; }
	?>
		<script type="text/javascript">
			//<![CDATA[
				jQuery(window).load(function(){
					setTimeout( function() {
						jQuery('.sugar-faqs-wrap').fadeIn(400);
						jQuery('.sugar-faqs-wrap').mfxAccordion({
							slideSpeed: 300,
							singleOption: <?php echo $singleOpen; ?>
						});
					}, 800 );
				});
			//]]>
		</script>
	<?php
}
add_action('wp_footer', 'sf_print_scripts');

function sf_echo_scripts()
{
	global $sf_options;
	if (!is_admin())
	{
		if(!empty($sf_options['css'])) { ?>
			<style type="text/css">
				<?php echo $sf_options['css']; ?>
			</style>
		<?php
		}
	}
}
add_action('wp_head', 'sf_echo_scripts');
