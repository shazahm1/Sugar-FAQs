<?php
/************************************
* Front end submission form for FAQs
************************************/

function sf_submission_form() 
{
	global $sfBaseDir;
	
	// output form jQuery
	$form .= '<script type="text/javascript">
		//<![CDATA[
		jQuery(function($){

			$("#sf_submission").submit(function() {
			  // validate and process form here
				var str = $(this).serialize();					 
				   $.ajax({
				   type: "POST",
				   url: "' . $sfBaseDir . 'includes/process-submission.php",
				   data: str,
				   success: function(msg){						
						$("#note").ajaxComplete(function(event, request, settings)
						{ 
							if(msg == "OK") // Message Sent? Show the Thank You message and hide the form
							{
								result = "Your question has been submitted. Thank you!";
								$("#sf_submission").fadeOut();
							}
							else
							{
								result = msg;
							}								 
							$(this).html(result);							 
						});					 
					}					 
				 });					 
				return false;
			});				

		}); // end jquery(function($))
		//]]> 
	</script>';	
	
	// form CSS
	$form .= '
	<style type="text/css">
		#sf_submission fieldset { margin: 20px 0; }
		#sf_submission input, #sf_submission textarea { width: 99%; }
		#sf_submission #sf_submit { width: auto; }
		#note { color: #0085b9; }
		#note .notification_error { color: #b81236; }
	</style>
	';
	
	// output form HTML
	$form .= '<form id="sf_submission">';
		$form .= '<fieldset>';
			$form .= '<div>';
				$form .= '<label for="sf_name">Your Name</label><br/>';
				$form .= '<input type="text" name="sf_name" id="sf_name"/>';
			$form .= '</div>';
			$form .= '<div>';
				$form .= '<label for="sf_email">Your E-Mail</label><br/>';
				$form .= '<input type="text" name="sf_email" id="sf_email"/>';
			$form .= '</div>';
			$form .= '<div>';
				$form .= '<label for="sf_title">Question Title</label><br/>';
				$form .= '<input type="text" name="sf_title" id="sf_title"/>';
			$form .= '</div>';
			$form .= '<div>';
				$form .= '<label for="sf_topic">Question Topic</label><br/>';
				$form .= '<select name="sf_topic" id="sf_topic">';
					$terms = get_terms('faq_topics', array('hide_empty' => 0));
					foreach($terms as $term) {
						$form .= '<option value="' . $term->name .'">' . $term->name . '</option>';
					}
				$form .= '</select>';
			$form .= '</div>';
			$form .= '<div>';
				$form .= '<label for="sf_question">Your Question</label><br/>';
				$form .= '<textarea name="sf_question" id="sf_question" rows="8">Ask your question here</textarea>';
			$form .= '</div>';
			$form .= '<div>';
				$math1 = rand(1,10);
				$math2 = rand(1,10);
				$form .= '<label for="sf_math">Validate that you are human: ' . $math1 . '+' . $math2 . '</label><br/>';
				$form .= '<input type="text" name="sf_math" id="sf_math"/><br/>';
				$form .= '<input type="hidden" name="sf_math_1" value="' . $math1 . '"/>';
				$form .= '<input type="hidden" name="sf_math_2" value="' . $math2 . '"/>';
				$form .= '<input type="hidden" name="sf_referrer" value="' . get_permalink() . '"/>';
				$form .= '<input type="submit" id="sf_submit" value="Send Question"/>';
			$form .= '</div>';
		$form .= '</fieldset>';
	$form .= '</form>';
	$form .= '<div id="note"></div>';

	return $form;
}
add_shortcode('faqs_form', 'sf_submission_form');
?>