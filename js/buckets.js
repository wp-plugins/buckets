jQuery(document).ready(function($){

		$('.bucket-shortcode').hover(function(){
			$('.bucket-shortcode-tooltip').not(':animated').fadeIn();
			def = $('.bucket-shortcode-tooltip').text();
		}, function(){
			$('.bucket-shortcode-tooltip').not(':animated').fadeOut('normal', function(){
				$('.bucket-shortcode-tooltip').text(def);
			});
		});

		$('.bucket-shortcode').zclip({
	        path:'../wp-content/plugins/buckets/js/ZeroClipboard.swf',
	        copy: $('#cody').text(),
	        afterCopy:function(){
	        	$('.bucket-shortcode-tooltip').text('Copied to clipboard!').fadeIn();
	        }
	    });

	});
