/**
 * Plugin: mfxAccordion
 * Author: Metaffex
 * Description: A jQuery accordion that uses CSS3 transitions for better browsers with a jQuery animation fallback for Internet Explorer
 */

(function($) {
	$.fn.mfxAccordion = function(options) {
		// Set up the options
		var o = $.extend({
			singleOption: true, // 'true' for only allowing one option open at once
			slideSpeed: 300 // the speed in milliseconds at which IE will animate
		}, options);
		
		// Multiple accordions supported
		$(this).each(function() {
			var $this = $(this);
			
			$this.children('.faq-section').each(function() {
				// Store some variables
				var $section = $(this);
				var contentHeight = $section.find('.faq-content').height();
				
				// Set the base heights
				if (!$section.hasClass('first')) {
					$section.find('.faq-content').height(0);
				} else {
					$section.find('.faq-content').height(contentHeight);
				}
				
				// Bind a click event to the trigger
				$section.find('.trigger').click(function(event) {
					// Determine if the content area is already visible
					var visible = ($(this).siblings('.faq-content').height() > 0);
					var $content = $(this).siblings('.faq-content');
					
					// If only one can be open at a time, close the rest
					if (o.singleOption) {
						if (!visible) {
							$this.children('.faq-section').removeClass('active-faq');
							
							if ($.browser.msie) {
								$this.find('.faq-content').stop(true, true).animate({ height: 0 }, o.slideSpeed);
							} else {
								$this.find('.faq-content').height(0);
							}
						}
					}
					
					// If it's visible, hide it
					if (visible) {
						if ($.browser.msie) {
							$content.stop(true, true).animate({ height: 0 }, o.slideSpeed);
						} else {
							$content.height(0);
						}
					} else {
						// Otherwise, show it
						if ($.browser.msie) {
							$content.stop(true, true).animate({ height: contentHeight }, o.slideSpeed);
						} else {
							$content.height(contentHeight);
						}
					}
					
					$section.toggleClass('active-faq');
				});	
			});
		});
	}
})(jQuery);
