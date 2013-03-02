jQuery(document).ready(function($){

	$('.bucket-shortcode').hover(function(){
		$('.bucket-shortcode-tooltip').not(':animated').fadeIn();
		def = $('.bucket-shortcode-tooltip').text();
	}, function(){
		$('.bucket-shortcode-tooltip').not(':animated').fadeOut('normal', function(){
			$('.bucket-shortcode-tooltip').text(def);
		});
	});

	$('.acf_buckets span.edit').live('click', function(){
		var url = $(this).attr('data-url');
		tb_show('Edit Bucket', url);
	});

	if (jQuery().zclip){
	    $('.bucket-shortcode #copy').zclip({
	        path:'../wp-content/plugins/buckets/js/ZeroClipboard.swf',
	        copy: $('#cody').text(),
	        afterCopy:function(){
	        	$('.bucket-shortcode-tooltip').text('Copied to clipboard!').fadeIn();
	        }
	    });
	}

});


function update_buckets(){
    var div = jQuery('.acf_buckets');
    if( div.length == 0 ) { return; }
    setTimeout(function(){
    	acf.buckets_update_results( div );
    	tb_remove()
    }, 2000);
}

/*
*	Update Buckets Sidebar Field Results
*/
(function($){	

	acf.is_clone_field = function( input )
	{
		if( input.attr('name') && input.attr('name').indexOf('[999]') != -1 )
		{
			return true;
		}
		
		return false;
	}

	$(document).live('acf/setup_fields', function(e, postbox){
		
		$(postbox).find('.acf_buckets').each(function(){
			// is clone field?
			if( acf.is_clone_field($(this).children('input[type="hidden"]')) )
			{
				//console.log('Clone Field: Relationship');
				return;
			}
			
			
			$(this).find('.relationship_right .relationship_list').sortable({
				axis: "y", // limit the dragging to up/down only
				items: '> li',
				forceHelperSize: true,
				forcePlaceholderSize: true,
				scroll: true
			});
			
			
			// load more
			$(this).find('.relationship_left .relationship_list').scrollTop(0).scroll( function(){
				
				// vars
				var div = $(this).closest('.acf_buckets');
				
				
				// validate
				if( div.hasClass('loading') )
				{
					return;
				}
				
				
				// Scrolled to bottom
				if( $(this).scrollTop() + $(this).innerHeight() >= $(this).get(0).scrollHeight )
				{
					var paged = parseInt( div.attr('data-paged') );
					
					div.attr('data-paged', (paged + 1) );
					
					acf.buckets_update_results( div );
				}

			});

			acf.buckets_update_results( $(this) );
    		
    	});
	});
	
	// add from left to right
	$('.acf_buckets .relationship_left .relationship_list a').live('click', function(){
		
		// vars
		var id = $(this).attr('data-post_id'),
			title = $(this).html(),
			div = $(this).closest('.acf_buckets'),
			max = parseInt(div.attr('data-max')),
			right = div.find('.relationship_right .relationship_list');
		
		
		// max posts
		if( right.find('a').length >= max )
		{
			alert( acf.text.relationship_max_alert.replace('{max}', max) );
			return false;
		}
		
		
		// can be added?
		if( $(this).parent().hasClass('hide') )
		{
			return false;
		}
		
		
		// hide / show
		$(this).parent().addClass('hide');
		
		
		// create new li for right side
		var new_li = div.children('.tmpl-li').html()
			.replace( /\{post_id}/gi, id )
			.replace( /\{title}/gi, title );

		// add new li
		right.append( new_li );
		
		// validation
		div.closest('.field').removeClass('error');
		
		return false;
		
	});
	
	
	// remove from right to left
	$('.acf_buckets .relationship_right .relationship_list a').live('click', function(){
		
		// vars
		var id = $(this).attr('data-post_id'),
			div = $(this).closest('.acf_buckets'),
			left = div.find('.relationship_left .relationship_list');
		
		
		// hide
		$(this).parent().remove();
		
		
		// show
		left.find('a[data-post_id="' + id + '"]').parent('li').removeClass('hide');
		
		
		return false;
		
	});
	
	
	// search
	$('.acf_buckets input.relationship_search').live('keyup', function()
	{	
		// vars
		var val = $(this).val(),
			div = $(this).closest('.acf_buckets');
			
		
		// update data-s
	    div.attr('data-s', val);
	    
	    
	    // new search, reset paged
	    div.attr('data-paged', 1);
	    
	    
	    // ajax
	    clearTimeout( acf.relationship_timeout );
	    acf.relationship_timeout = setTimeout(function(){
	    	acf.buckets_update_results( div );
	    }, 250);
	    
	    return false;
	    
	})
	.live('focus', function(){
		$(this).siblings('label').hide();
	})
	.live('blur', function(){
		if($(this).val() == "")
		{
			$(this).siblings('label').show();
		}
	});
	
	
	// hide results
	acf.buckets_hide_results = function( div ){
		
		// vars
		var left = div.find('.relationship_left .relationship_list'),
			right = div.find('.relationship_right .relationship_list');
			
			
		// apply .hide to left li's
		left.find('a').each(function(){
			
			var id = $(this).attr('data-post_id');
			
			if( right.find('a[data-post_id="' + id + '"]').exists() )
			{
				$(this).parent().addClass('hide');
			}
			
		});
		
	}

	// update results
	acf.buckets_update_results = function( div ){

		// add loading class, stops scroll loading
		div.addClass('loading');
		
		
		// vars
		var s = div.attr('data-s'),
			paged = parseInt( div.attr('data-paged') ),
			taxonomy = div.attr('data-taxonomy'),
			left = div.find('.relationship_left .relationship_list'),
			right = div.find('.relationship_right .relationship_list');
		
		
		// get results
	    $.ajax({
			url: ajaxurl,
			type: 'post',
			dataType: 'html',
			data: { 
				'action' : 'acf_get_bucket_results', 
				's' : s,
				'paged' : paged
			},
			success: function( html ){
				div.removeClass('no-results').removeClass('loading');
				
				// new search?
				if( paged == 1 )
				{
					left.find('li:not(.load-more)').remove();
				}
				
				
				// no results?
				if( !html )
				{
					div.addClass('no-results');
					return;
				}
				
				
				// append new results
				left.find('.load-more').before( html );
				
				
				// less than 10 results?
				var ul = $('<ul>' + html + '</ul>');
				if( ul.find('li').length < 10 )
				{
					div.addClass('no-results');
				}
				
				// hide values
				acf.buckets_hide_results( div );
				
			}
		});
	};


})(jQuery);