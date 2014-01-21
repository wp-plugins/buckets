jQuery(document).ready(function($){
	
	$('.acf_buckets li').live('mouseover', function(){
		var pID = $(this).find('a').data('post_id');
		if ($(this).find('span.edit').length==0){
			$(this).prepend('<span class="edit" data-url="' + window.location.protocol + '//' + window.location.host + window.location.pathname + '?post=' + pID + '&action=edit&popup=true&TB_iframe=1">Edit</span>')	
		}
	}); 

	$('.acf_buckets span.edit').live('click', function(){
		var url = $(this).attr('data-url');
		tb_show('Edit Bucket', url);
	});

});


function update_buckets(){
    if( jQuery('.acf_buckets').length == 0 ) { return; }
    setTimeout(function(){
    	acf.fields.relationship.fetch();
    	tb_remove()
    }, 2000);
}
