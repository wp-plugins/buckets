jQuery(function($){

    $('.button-primary').click(function(e){
        parent.eval('update_buckets()');
        e.stopPropagation(); // prevents click from firing twice
    });

});