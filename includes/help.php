<?php

// documentation for sliders manager page
function sf_contextual_help($contextual_help, $screen_id, $screen) {	

		$contextual_help = '
			<h3>Sugar FAQs Help</h3>		
			<h4>Selector IDs and Classes</h4>
			<p>You may use the following selectors in your custom CSS to style the FAQs exactly to your liking:</p>
			<ul>
				<li><em>.sugar-faqs-wrap</em> - this is the DIV wrapper around the FAQs</li>
				<li><em>.section</em> - each FAQ is wrapped in DIV.section
					<ul>
						<li><em>.section.active</em> - this may be used to target the currently display FAQ</li>
						<li><em>.section.color</em> - the color class depends on the style selected below. If "blue is selected, then your class is <em>.section.blue</em></li>
					</ul>
				</li>
				<li><em>.trigger</em> - this is the H3 class of the clickable FAQ title</li>
				<li><em>.content</em> - the DIV.content is the wrapper of the FAQ answer, displayed when open</li>
				<li><em>.faq-topic<?em> - this is the class name for the H2 used for Topic titles</li>
			</ul>
        ';
		return $contextual_help;
}
if (isset($_GET['page']) && $_GET['page'] == 'sugar-faqs-settings') 
{
	add_action('contextual_help', 'sf_contextual_help', 10, 3);
}
?>