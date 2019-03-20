==========================================================
== SUGAR FAQs Plugin Usage Instructions ==================
==========================================================

Plugin Author: Pippin Williamson
Plugin URL: http://pippinsplugins.com/sugar-faqs-wordpress-faqs-management

For support, please contact me via my profile page on Code Canyon:
 - http://codecanyon.net/user/mordauk/profile


=============================
== Installation =============
=============================

1. Unzip the file downloaded from Code Canyon
2. Upload the "sugar-faqs" folder to /wp-content/plugins/
3. Navigate to the WordPress Plugins page and click "Activate" on "Sugar FAQs for WordPress"

=============================
== Configuration ============
=============================

There are a few basic settings that you may use to customize the appearance of the FAQs.

The settings page is located at "Settings > FAQ Settings" from your WordPress Dashboard.

 == Style ==
 
	- The color scheme used for your FAQs may be selected from the drop down here. This value may be overwritten by short code attributes.
	- You can define the widget of the FAQs by entering a value here. This value may be overwritten by short code attributes.
	- "Disable icons?" will disable the ? icon if checked.
	- "Enable 'Single Open' mode" will make it so that only one FAQ accordion may be opened at once.
	
 == Notifications ==	
 
	By checking this box, you will enable email notifications to the admin email account anytime a new FAQ is submitted and needs reviewed.
	
 == Custom CSS ==
 
	You may enter any custom CSS here you wish. This is primarily for advanced users and those wishing to modify the default layout.
	

=============================
== SHORT CODES ==============
=============================

All FAQs may be displayed using WordPress short codes. These are entered into post/page content and are very simple to use.

To display a list of all FAQs with default layout options, use:

[faqs]

This will display a list of FAQs in accordion style that are separated into topics. 
It will use the color scheme defined in the FAQs Settings page.

The short code accepts a variety of parameters.
	- topicalize
	- topic
	- hierarchical
	- width
	- color
	
The default behavior is: [faqs topicalize=true topic='' width='' color='']

"topicalize" accepts either "true" or "false". if set to "true", the FAQs will be separated by Topics

"topic" accepts the slug of a particular FAQ Topic.

"hierarchial" accepts either "true" or "false". If set to "true" and "topic" is set to a parent topic, the FAQs will be separated by respective child topics. This works exactly the same way as the "topicalize" option, except that only those faqs in child topics of the "topic" specified will be shown.

"width" accepts an integer, such as "400". This is a value in pixels.

"color" accepts any color name from the available list. Options are:
	default
	light
	gray
	dark
	black
	blue
	red
	green
	yellow
	
So, if you wanted to display a non-topicalized list of FAQs that are filed under the "Technical" topic, 
and you wish them to be 500 px wide, and for them to have the "blue" color scheme, you would use:

[faqs topicalize=false topic=technical width=500 color=blue]


There is also a shortcode that can be used to display the FAQ submission form. Refer to the FAQ Submission Form section below.

=============================
== Widget ===================
=============================

Sugar FAQs comes with one custom widget called "FAQ Topics". It allows you to display a list of all FAQ Topics in
your widgetized sidebar. It has two options: one to display the number of FAQs in each topic, and one to enable hierarchical display,
meaning that "child topics" will be indented beneath their parent.

Each Topic displayed by the widget will be linked to its Topic archive. This means that it will show every FAQ in that topic
and will use your theme's default styles. Not the accordion style.

=============================
== FAQ Submission Form ======
=============================

You can use the [faqs_form] shortcode in any post or page to display the submission form. This form will allow site visitors
to submit their own questions. When a question is submitted, a new FAQ is created in your WordPress FAQs section and set to Pending, meaning
that an admin must login and approve the FAQ before it is published and visible on the site.

The submission form is ajax enabled, so there is no page reload.