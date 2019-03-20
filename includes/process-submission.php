<?php

// enable wordpress functions
$oldURL = dirname(__FILE__);
$newURL = str_replace(DIRECTORY_SEPARATOR . 'wp-content' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . 'sugar-faqs' . DIRECTORY_SEPARATOR . 'includes', '', $oldURL);
include($newURL . DIRECTORY_SEPARATOR . 'wp-load.php');

// load the plugin settings
$sf_options = get_option( 'sf_settings' );

// validates the email given by the question submitter
function sf_validate_email($email)
{
	/*
	(Name) Letters, Numbers, Dots, Hyphens and Underscores
	(@ sign)
	(Domain) (with possible subdomain(s) ).
	Contains only letters, numbers, dots and hyphens (up to 255 characters)
	(. sign)
	(Extension) Letters only (up to 10 (can be increased in the future) characters)
	*/

	$regex = '/([a-z0-9_.-]+)'. # name

	'@'. # at

	'([a-z0-9.-]+){2,255}'. # domain & possibly subdomains

	'.'. # period

	'([a-z]+){2,10}/i'; # domain extension 

	if($email == '') { 
		return false;
	}
	else {
		$eregi = preg_replace($regex, '', $email);
	}

	return empty($eregi) ? true : false;
}

/************************************
* Process Front end Submissions
************************************/

if(!$_POST['sf_name'])
{
	$error .= 'Please enter your name.<br />';
}

// Check email

if(!$_POST['sf_email'])
{
	$error .= 'Please enter an e-mail address.<br />';
}

if(!$_POST['sf_title'])
{
	$error .= 'Please enter a question title.<br />';
}

if($_POST['sf_email'] && !sf_validate_email($_POST['sf_email']))
{
	$error .= 'Please enter a valid e-mail address.<br />';
}

// validate human submission
if(!$_POST['sf_math'])
{
	$error .= 'Please validate that you are human<br/>';
}
if($_POST['sf_math'] != $_POST['sf_math_1'] + $_POST['sf_math_2'])
{
	$error .= 'Your math is incorrect<br/>';
}

// Check message (length)

if(!$_POST['sf_question'] || strlen($_POST['sf_question']) < 20 || $_POST['sf_question'] == 'Ask your question here')
{
	$error .= "Please ask your question. It should have at least 20 characters.<br />";
}

if(!$error) // proceed to creating the FAQ because there were no errors
{
	// create the pending FAQ
	$faq = wp_insert_post(array(
			'post_title'	=> stripslashes(strip_tags($_POST['sf_title'])),
			'post_content'	=> stripslashes(strip_tags($_POST['sf_question'])),
			'post_status' 	=> 'pending',
			'post_author'	=> 1,
			'post_type'		=> 'faqs'
		)
	);
	
	// proceed if the FAQ was created successfully
	if($faq) {
	
		// get the term object of the selected topic
		$topic = get_term_by('name', $_POST['sf_topic'], 'faq_topics');
		
		// set the question topic
		wp_set_object_terms($faq, $topic->slug, 'faq_topics');
		
		// enter the author name into post meta
		add_post_meta($faq, 'sf_post_meta_author_name', stripslashes(strip_tags($_POST['sf_name'])));
		
		// enter the author email into post meta
		add_post_meta($faq, 'sf_post_meta_author_email', stripslashes(strip_tags($_POST['sf_email'])));
		add_post_meta($faq, 'sf_post_meta_submitted', true);
		
		// setup the email message
		$message .= 'A new question has been submitted at ' . get_bloginfo('url') . ' and needs to be reviewed. Go to your FAQs page to review the question.';
		
		if($sf_options['email_notifications'] == true) {
			wp_mail(get_bloginfo('admin_email'), 'New Question Submitted', $message);
		}
		echo 'OK'; // Tells jQuery everything worked okay
	
	} else {
		$error .= 'Sorry, something went wrong. Please try again later.';
	}
} 
else 
{
	echo '<div class="notification_error">'.$error.'</div>'; // set up error div for jQuery/Ajax
}

?>